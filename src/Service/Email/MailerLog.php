<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Service\Email;

use Doctrine\ORM\EntityManagerInterface;
use Optime\Email\Bundle\Entity\EmailLog;
use Throwable;

/**
 * @author Manuel Aguirre
 */
class MailerLog
{
    public function __construct(
        private Mailer $mailer,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function resend(EmailLog $emailLog): bool
    {
        if (!$emailLog->canResend()) {
            return false;
        }

        try {
            $variables = $emailLog->getVariables();
            $variables['_locale'] = $emailLog->getLocale();

            $this->mailer->send(
                $emailLog->getTemplate(),
                $variables,
                EmailRecipient::fromLog($emailLog),
                $emailLog,
            );

            return true;
        } catch (Throwable $exception) {
            $emailLog->addError($exception);
        } finally {
            $this->entityManager->persist($emailLog);
            $this->entityManager->flush();
        }

        return false;
    }
}
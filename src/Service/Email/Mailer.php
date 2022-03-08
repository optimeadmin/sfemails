<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Service\Email;

use Optime\Email\Bundle\Entity\EmailLog;
use Optime\Email\Bundle\Entity\EmailTemplate;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

/**
 * @author Manuel Aguirre
 */
class Mailer
{
    private string|null|false $loggedUserIdentifier = false;

    public function __construct(
        private MailerInterface $mailer,
        private EmailFactory $emailFactory,
    ) {
    }

    public function send(
        EmailTemplate $template,
        array $templateData,
        EmailRecipientInterface $recipient,
        ?EmailLog $emailLog = null,
    ): void {
        $email = $this->emailFactory->fromTemplate($template, $templateData);
        $email->to($this->recipientToAddress($recipient));
        $emailLog?->setEmail($email);

        $this->mailer->send($email);

        $emailLog?->confirmSend();
    }

    private function recipientToAddress(EmailRecipientInterface $recipient): Address
    {
        return new Address($recipient->getEmail(), $recipient->getName());
    }
}
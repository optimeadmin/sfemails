<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Service\Email;

use Optime\Email\Bundle\Entity\EmailLog;
use Optime\Email\Bundle\Service\Email\Recipient\EmailRecipientInterface;
use Throwable;

/**
 * @author Manuel Aguirre
 */
class MailerIntent
{
    private Throwable|null $lastError = null;
    private EmailLog|null $lastLog = null;

    public function __construct(
        private Mailer $mailer,
        private MailerIntentUtils $utils,
        private TemplateData $templateData,
    ) {
    }

    public function send(
        array $templateVars,
        EmailRecipientInterface $recipient,
    ): bool {
        $templateVars = $this->utils->normalizeVariables($templateVars);
        $locale = $templateVars['_locale'];

        $this->lastLog = $log = EmailLog::create(
            $locale,
            $this->templateData,
            $recipient,
            $templateVars,
            $this->utils->getLoggedUserIdentifier(),
        );

        try {
            if (!$this->isValidIntent()) {
                return false;
            }

            $this->mailer->send(
                $this->templateData->getTemplate(),
                $templateVars,
                $recipient,
                $log
            );

            return true;
        } catch (Throwable $exception) {
            $log->addError($exception);
            $this->lastError = $exception;
        } finally {
            $this->utils->saveLog($log);
        }

        return false;
    }

    public function getLastError(): ?Throwable
    {
        return $this->lastError;
    }

    public function getLastLog(): ?EmailLog
    {
        return $this->lastLog;
    }

    public function isValidIntent(): bool
    {
        return $this->templateData->hasTemplate();
    }
}
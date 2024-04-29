<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Service\Email;

use Optime\Email\Bundle\Entity\EmailLog;
use Optime\Email\Bundle\Entity\EmailTemplate;
use Optime\Email\Bundle\Service\Email\Recipient\EmailRecipientInterface;
use Optime\Email\Bundle\Service\Template\Variable\TemplateVarsNormalizer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

/**
 * @author Manuel Aguirre
 */
class Mailer
{
    private string|null|false $loggedUserIdentifier = false;

    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly EmailFactory $emailFactory,
        private readonly TemplateVarsNormalizer $varsNormalizer,
    ) {
    }

    public function send(
        EmailTemplate $template,
        array $templateData,
        EmailRecipientInterface $recipient,
        ?EmailLog $emailLog = null,
    ): void {
        $templateData = $this->varsNormalizer->normalize($templateData);

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
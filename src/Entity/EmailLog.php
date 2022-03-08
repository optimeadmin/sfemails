<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Optime\Email\Bundle\Service\Email\EmailRecipientInterface;
use Optime\Email\Bundle\Service\Email\TemplateData;
use Optime\Util\Entity\Traits\DatesTrait;
use Optime\Util\Entity\Traits\ExternalUuidTrait;
use Symfony\Component\Mime\Email;
use Throwable;

#[ORM\Table('emails_bundle_email_log')]
#[ORM\Entity]
class EmailLog
{
    use ExternalUuidTrait, DatesTrait;

    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    private readonly ?int $id;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'email_template_id')]
    private ?EmailTemplate $template;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $subject;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $content;

    #[ORM\Column]
    private string $recipient;

    #[ORM\Column]
    private string $emailCode;

    #[ORM\Column]
    private string $recipientIdentifier;

    #[ORM\Column(nullable: true)]
    private ?string $sessionUserIdentifier;

    #[ORM\Column(type: 'string', enumType: EmailLogStatus::class, nullable: true)]
    private EmailLogStatus $status;

    #[ORM\Column(nullable: true)]
    private ?string $failureMessage;

    public function __construct(
        TemplateData $templateData,
        EmailRecipientInterface $recipient,
        ?string $sessionUserIdentifier,
        ?string $info = null
    ) {
        $this->emailCode = $templateData->getEmailCode();
        $this->recipient = $recipient->getEmail();
        $this->recipientIdentifier = $recipient->getRecipientId();
        $this->sessionUserIdentifier = $sessionUserIdentifier;
        $this->template = $templateData->getTemplate();
        $this->status = $this->template ? EmailLogStatus::pending : EmailLogStatus::no_template;

        if (null !== $info) {
            $this->failureMessage = $info;
        }
    }

    public static function create(
        TemplateData $templateData,
        EmailRecipientInterface $recipient,
        ?string $sessionUserIdentifier,
    ): self {
        if (null === $templateData->getConfig()) {
            return new self(
                $templateData,
                $recipient,
                $sessionUserIdentifier,
                'Undefined emailConfig'
            );
        }

        if (null === $templateData->getTemplate()) {
            $app = $templateData->getApp();
            return new self(
                $templateData,
                $recipient,
                $sessionUserIdentifier,
                $app
                    ? 'Undefined EmailTemplate for App#' . $app->getId()
                    : 'Undefined EmailTemplate',
            );
        }

        return new self(
            $templateData,
            $recipient,
            $sessionUserIdentifier,
        );
    }

    public function addError(Throwable $error): void
    {
        $this->status = EmailLogStatus::error;
        $this->failureMessage = $error->getMessage();
    }

    public function setEmail(Email $email): void
    {
        $this->subject = $email->getSubject();
        $this->content = $email->getHtmlBody();
    }

    public function confirmSend(): void
    {
        $this->status = EmailLogStatus::send;
    }
}
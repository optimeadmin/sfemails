<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Optime\Email\Bundle\Repository\EmailLogRepository;
use Optime\Email\Bundle\Service\Email\Recipient\EmailRecipientInterface;
use Optime\Email\Bundle\Service\Email\TemplateData;
use Optime\Util\Entity\Traits\DatesTrait;
use Optime\Util\Entity\Traits\ExternalUuidTrait;
use Symfony\Component\Mime\Email;
use Symfony\Component\Uid\Uuid;
use Throwable;
use Traversable;
use function get_resource_id;
use function is_iterable;
use function is_object;
use function is_resource;
use function iterator_to_array;

#[ORM\Table('emails_bundle_email_log')]
#[ORM\Entity(EmailLogRepository::class)]
#[ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")]
class EmailLog
{
    use ExternalUuidTrait, DatesTrait;

    public const UUID_VARIABLE = '_email_id';

    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    private readonly ?int $id;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'email_template_id')]
    private ?EmailTemplate $template;

    #[ORM\Column]
    private bool $resend = false;

    #[ORM\Column]
    private string $locale;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $subject;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $content;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $variables;

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

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $failureMessage;

    public function __construct(
        string $locale,
        TemplateData $templateData,
        EmailRecipientInterface $recipient,
        array $templateVars,
        ?string $sessionUserIdentifier,
        ?string $info = null
    ) {
        $this->locale = $locale;
        $this->emailCode = $templateData->getEmailCode();
        $this->recipient = $recipient->getEmail();
        $this->recipientIdentifier = $recipient->getRecipientId();
        $this->variables = $this->normalizeVars($templateVars);
        $this->sessionUserIdentifier = $sessionUserIdentifier;
        $this->template = $templateData->getTemplate();
        $this->status = $this->template ? EmailLogStatus::pending : EmailLogStatus::no_template;

        if (null !== $info) {
            $this->failureMessage = $info;
        }
        if (isset($templateVars[self::UUID_VARIABLE]) && $templateVars[self::UUID_VARIABLE] instanceof Uuid) {
            $this->uuid = $templateVars[self::UUID_VARIABLE];
            unset($this->variables[self::UUID_VARIABLE]);
        }

    }

    public static function create(
        string $locale,
        TemplateData $templateData,
        EmailRecipientInterface $recipient,
        array $templateVars,
        ?string $sessionUserIdentifier,
    ): self {
        if (null === $templateData->getConfig()) {
            return new self(
                $locale,
                $templateData,
                $recipient,
                $templateVars,
                $sessionUserIdentifier,
                'Undefined emailConfig'
            );
        }

        if (null === $templateData->getTemplate()) {
            $app = $templateData->getApp();
            return new self(
                $locale,
                $templateData,
                $recipient,
                $templateVars,
                $sessionUserIdentifier,
                $app
                    ? 'Undefined EmailTemplate for App#' . $app->getId()
                    :
                    ($templateData->isAppFromResolver()
                        ? 'App Resolving Failed'
                        : 'Undefined EmailTemplate')
                ,
            );
        }

        return new self(
            $locale,
            $templateData,
            $recipient,
            $templateVars,
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
        if ($this->status == EmailLogStatus::error) {
            $this->resend = true;
            $this->failureMessage = '';
        }

        $this->status = EmailLogStatus::send;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function isResend(): bool
    {
        return $this->resend;
    }

    public function getTemplate(): ?EmailTemplate
    {
        return $this->template;
    }

    public function getVariables(): ?array
    {
        return $this->variables;
    }

    public function getRecipient(): string
    {
        return $this->recipient;
    }

    public function getRecipientIdentifier(): string
    {
        return $this->recipientIdentifier;
    }

    public function getEmailCode(): string
    {
        return $this->emailCode;
    }

    public function getSessionUserIdentifier(): ?string
    {
        return $this->sessionUserIdentifier;
    }

    public function getStatus(): EmailLogStatus
    {
        return $this->status;
    }

    public function getFailureMessage(): ?string
    {
        return $this->failureMessage;
    }

    public function canResend(): bool
    {
        if ($this->getStatus() == EmailLogStatus::send) {
            return false;
        }
        if ($this->getStatus() == EmailLogStatus::no_template) {
            return false;
        }
        if (null === $this->template) {
            return false;
        }

        return true;
    }

    private function normalizeVars(array $vars): array
    {
        foreach ($vars as $index => &$var) {
            if (is_object($var)) {
                $var = '(object) ' . $var::class;
            } elseif (is_resource($var)) {
                $var = '(resource) ' . get_resource_id($var);
            } elseif (is_iterable($var)) {
                if ($var instanceof Traversable) {
                    $var = iterator_to_array($var);
                }

                $var = $this->normalizeVars($var);
            }
        };

        return $vars;
    }
}
<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Translatable;
use Optime\Email\Bundle\Constraints\UniqueTemplate;
use Optime\Email\Bundle\Dto\EmailTemplateDto;
use Optime\Email\Bundle\Repository\EmailTemplateRepository;
use Optime\Util\Entity\Traits\DatesTrait;
use Optime\Util\Entity\Traits\ExternalUuidTrait;
use Optime\Util\Translation\TranslationsAwareInterface;
use Optime\Util\Translation\TranslationsAwareTrait;
use Stringable;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Table('emails_bundle_email_template')]
#[ORM\Entity(repositoryClass: EmailTemplateRepository::class)]
#[ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")]
#[UniqueTemplate(errorPath: 'configUuid')]
class EmailTemplate implements TranslationsAwareInterface, Stringable
{
    use ExternalUuidTrait, DatesTrait, TranslationsAwareTrait;

    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    private readonly ?int $id;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'email_app_id', nullable: false)]
    #[NotBlank]
    private EmailAppInterface $app;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'email_master_id', nullable: false)]
    #[NotBlank]
    private EmailMaster $config;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'email_layout_id', nullable: true)]
    private ?EmailLayout $layout = null;

    #[ORM\Column(type: 'text')]
    #[Translatable]
    #[NotBlank]
    private ?string $subject = null;

    #[ORM\Column(type: 'text')]
    #[Translatable]
    #[NotBlank]
    private ?string $content = null;

    #[ORM\Column]
    private bool $active;

    public function __construct()
    {
        $this->active = true;
    }

    public static function create(
        EmailTemplateDto $dto,
        EmailAppInterface $app,
        EmailMaster $config,
        ?EmailLayout $layout = null,
    ): self {
        $entity = new self();
        $entity->update($dto, $app, $config, $layout);

        return $entity;
    }

    public function getId(): ?int
    {
        return $this->id ?? null;
    }

    public function getApp(): EmailAppInterface
    {
        return $this->app;
    }

    public function getConfig(): EmailMaster
    {
        return $this->config;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setApp(EmailAppInterface $app): void
    {
        $this->app = $app;
    }

    public function setConfig(EmailMaster $config): void
    {
        $this->config = $config;
    }

    public function setSubject(?string $subject): void
    {
        $this->subject = $subject;
    }

    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function getLayout(): EmailLayout
    {
        return $this->getCustomLayout() ?? $this->getConfig()->getLayout();
    }

    public function __toString(): string
    {
        return $this->getContent();
    }

    public function setCustomLayout(?EmailLayout $layout): void
    {
        $this->layout = $layout;
    }

    public function getCustomLayout(): ?EmailLayout
    {
        return $this->layout;
    }

    public function update(
        EmailTemplateDto $dto,
        EmailAppInterface $app,
        EmailMaster $config,
        ?EmailLayout $layout = null
    ): void {
        $this->setApp($app);
        $this->setConfig($config);
        $this->setCustomLayout($layout);
        $this->setSubject((string)$dto->subject);
        $this->setContent((string)$dto->content);
        $this->setActive($dto->active);
    }
}
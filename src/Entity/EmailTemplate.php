<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Translatable;
use Optime\Email\Bundle\Repository\EmailTemplateRepository;
use Optime\Util\Entity\Traits\DatesTrait;
use Optime\Util\Entity\Traits\ExternalUuidTrait;
use Optime\Util\Translation\TranslationsAwareInterface;
use Optime\Util\Translation\TranslationsAwareTrait;

#[ORM\Table('emails_bundle_email_template')]
#[ORM\Entity(EmailTemplateRepository::class)]
class EmailTemplate implements TranslationsAwareInterface
{
    use ExternalUuidTrait, DatesTrait, TranslationsAwareTrait;

    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    private readonly ?int $id;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'email_owner_id', nullable: false)]
    private EmailOwner $owner;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'email_master_id', nullable: false)]
    private EmailMaster $config;

    #[ORM\Column(type: 'text')]
    #[Translatable]
    private string $subject;

    #[ORM\Column(type: 'text')]
    #[Translatable]
    private string $content;

    #[ORM\Column]
    private bool $active;

    public function getOwner(): EmailOwner
    {
        return $this->owner;
    }

    public function getConfig(): EmailMaster
    {
        return $this->config;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setOwner(EmailOwner $owner): void
    {
        $this->owner = $owner;
    }

    public function setConfig(EmailMaster $config): void
    {
        $this->config = $config;
    }

    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }
}
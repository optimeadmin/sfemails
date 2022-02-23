<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Translatable;
use Optime\Util\Entity\Traits\DatesTrait;
use Optime\Util\Entity\Traits\ExternalUuidTrait;
use Optime\Util\Translation\TranslationsAwareInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Table('emails_bundle_email_master')]
#[ORM\Entity]
#[ORM\UniqueConstraint('email_master_code', ['code'])]
#[UniqueEntity("code")]
class EmailMaster implements TranslationsAwareInterface
{
    use ExternalUuidTrait, DatesTrait;

    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    private readonly ?int $id;

    #[ORM\Column]
    #[NotBlank]
    private string $code;

    #[ORM\Column(type: 'text')]
    #[NotBlank]
    private string $description;

    #[ORM\Column(type: 'text')]
    #[Translatable]
    private string $layout;

    #[ORM\Column]
    private bool $editable;

    #[ORM\Column]
    #[NotBlank]
    private string $target;

    private ?string $transLocale = null;

    public function getCode(): string
    {
        return $this->code;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getLayout(): string
    {
        return $this->layout;
    }

    public function isEditable(): bool
    {
        return $this->editable;
    }

    public function getTarget(): string
    {
        return $this->target;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setLayout(string $layout): void
    {
        $this->layout = $layout;
    }

    public function setEditable(bool $editable): void
    {
        $this->editable = $editable;
    }

    public function setTarget(string $target): void
    {
        $this->target = $target;
    }

    public function getCurrentContentsLocale(): ?string
    {
        return $this->transLocale;
    }

    public function setCurrentContentsLocale(string $locale): void
    {
        $this->transLocale = $locale;
    }
}
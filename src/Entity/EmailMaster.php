<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Translatable;
use Optime\Util\Entity\Traits\DatesTrait;
use Optime\Util\Entity\Traits\ExternalUuidTrait;

#[ORM\Table('emails_bundle_email_master')]
#[ORM\Entity]
#[ORM\UniqueConstraint('email_master_description', ['description'])]
class EmailMaster
{
    use ExternalUuidTrait, DatesTrait;

    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    private readonly ?int $id;

    #[ORM\Column]
    private string $code;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column(type: 'text')]
    #[Translatable]
    private string $layout;

    #[ORM\Column]
    private bool $editable;

    #[ORM\Column]
    private string $target;

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

}
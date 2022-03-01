<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Optime\Email\Bundle\Repository\EmailMasterRepository;
use Optime\Util\Entity\Traits\DatesTrait;
use Optime\Util\Entity\Traits\ExternalUuidTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Table('emails_bundle_email_master')]
#[ORM\Entity(repositoryClass: EmailMasterRepository::class)]
#[ORM\UniqueConstraint('email_master_code', ['code'])]
#[UniqueEntity("code")]
class EmailMaster
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

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[NotBlank]
    private EmailLayout $layout;

    #[ORM\Column]
    private bool $editable;

    #[ORM\Column]
    #[NotBlank]
    private string $target;

    public function getCode(): string
    {
        return $this->code;
    }

    public function getDescription(): string
    {
        return $this->description;
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

    public function setEditable(bool $editable): void
    {
        $this->editable = $editable;
    }

    public function setTarget(string $target): void
    {
        $this->target = $target;
    }

    public function getLayout(): EmailLayout
    {
        return $this->layout;
    }

    public function setLayout(EmailLayout $layout): void
    {
        $this->layout = $layout;
    }
}
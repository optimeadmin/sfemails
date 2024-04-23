<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Optime\Email\Bundle\Dto\ConfigDto;
use Optime\Email\Bundle\Repository\EmailMasterRepository;
use Optime\Util\Entity\Traits\DatesTrait;
use Optime\Util\Entity\Traits\ExternalUuidTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Table('emails_bundle_email_master')]
#[ORM\Entity(repositoryClass: EmailMasterRepository::class)]
#[ORM\UniqueConstraint('email_master_code', ['code'])]
#[ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")]
#[UniqueEntity("code", message: "This email code already exists")]
class EmailMaster
{
    use ExternalUuidTrait, DatesTrait;

    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    private readonly ?int $id;

    #[ORM\Column(type: 'string', length: 50)]
    #[NotBlank]
    #[Length(max: 50)]
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

    #[ORM\Column(length: 50)]
    #[NotBlank]
    #[Length(max: 50)]
    private string $target;

    public static function create(ConfigDto $dto, EmailLayout $layout): self
    {
        $entity = new self();
        $entity->update($dto, $layout);

        return $entity;
    }

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

    public function __toString(): string
    {
        return $this->getCode();
    }

    public function update(ConfigDto $dto, ?EmailLayout $layout = null): void
    {
        $this->setCode($dto->code);
        $this->setDescription($dto->description);
        $this->setTarget($dto->target);
        $this->setEditable($dto->editable);
        $layout && $this->setLayout($layout);
    }
}
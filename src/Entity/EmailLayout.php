<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Translatable;
use Optime\Email\Bundle\Repository\EmailLayoutRepository;
use Optime\Util\Entity\Traits\DatesTrait;
use Optime\Util\Entity\Traits\ExternalUuidTrait;
use Optime\Util\Translation\TranslationsAwareInterface;
use Optime\Util\Translation\TranslationsAwareTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Table('emails_bundle_email_layout')]
#[ORM\Entity(repositoryClass: EmailLayoutRepository::class)]
#[UniqueEntity('description')]
class EmailLayout implements TranslationsAwareInterface
{
    use ExternalUuidTrait, DatesTrait, TranslationsAwareTrait;

    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    private readonly ?int $id;

    #[ORM\Column(type: 'text')]
    #[NotBlank]
    private string $description;

    #[ORM\Column(type: 'text')]
    #[Translatable]
    #[NotBlank]
    private string $content;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function __toString(): string
    {
        return $this->getDescription();
    }
}
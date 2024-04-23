<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Translatable;
use Optime\Email\Bundle\Dto\EmailLayoutDto;
use Optime\Email\Bundle\Repository\EmailLayoutRepository;
use Optime\Util\Entity\Traits\DatesTrait;
use Optime\Util\Entity\Traits\ExternalUuidTrait;
use Optime\Util\Translation\TranslationsAwareInterface;
use Optime\Util\Translation\TranslationsAwareTrait;
use Stringable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\NotBlank;
use function sprintf;

#[ORM\Table('emails_bundle_email_layout')]
#[ORM\Entity(repositoryClass: EmailLayoutRepository::class)]
#[ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")]
#[UniqueEntity('description')]
class EmailLayout implements TranslationsAwareInterface, Stringable
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
    private ?string $content = '';

    public static function create(EmailLayoutDto $dto): self
    {
        $layout = new self();
        $layout->update($dto);

        return $layout;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    public function __toString(): string
    {
        return (string)$this->getContent();
    }

    public function getLabel(): string
    {
        return sprintf('%s (#%s)', $this->getDescription(), $this->getId());
    }

    public function update(EmailLayoutDto $dto): void
    {
        $dto->description && $this->setDescription($dto->description);
        $dto->content && $this->setContent((string)$dto->content);
    }
}
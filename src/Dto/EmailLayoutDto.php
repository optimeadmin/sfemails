<?php
/**
 * @author Manuel Aguirre
 */

declare(strict_types=1);

namespace Optime\Email\Bundle\Dto;

use Optime\Email\Bundle\Constraints\TwigContent;
use Optime\Email\Bundle\Entity\EmailLayout;
use Optime\Util\Entity\Embedded\Date;
use Optime\Util\Translation\TranslatableContent;
use Optime\Util\Translation\Validation\TranslatableConstraint;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Manuel Aguirre
 */
class EmailLayoutDto
{
    public ?int $id = null;
    public ?Uuid $uuid = null;
    #[NotBlank]
    public ?string $description = null;

    #[NotBlank]
    #[TranslatableConstraint([new NotBlank(), new TwigContent()], '')]
    public ?TranslatableContent $content = null;
    public ?Date $dates = null;

    public static function fromEntity(EmailLayout $layout): self
    {
        $dto = new self();
        $dto->id = $layout->getId();
        $dto->uuid = $layout->getUuid();
        $dto->description = $layout->getDescription();
        $dto->content = TranslatableContent::pending($layout, 'content');
        $dto->dates = $layout->getDates();

        return $dto;
    }
}
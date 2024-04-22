<?php
/**
 * @author Manuel Aguirre
 */

declare(strict_types=1);

namespace Optime\Email\Bundle\Dto;

use Optime\Email\Bundle\Entity\EmailLayout;
use Optime\Util\Entity\Embedded\Date;
use Optime\Util\Serializer\Normalizer\TranslatableContentNormalizer;
use Optime\Util\Translation\TranslatableContent;
use Symfony\Component\Serializer\Attribute\Context;
use Symfony\Component\Uid\Uuid;

/**
 * @author Manuel Aguirre
 */
#[Context([TranslatableContentNormalizer::AUTO_REFRESH => true])]
class EmailLayoutDto
{
    public ?int $id = null;
    public ?Uuid $uuid = null;
    public ?string $description = null;
    public ?TranslatableContent $contents = null;
    public ?Date $dates = null;

    public static function fromEntity(EmailLayout $layout): self
    {
        $dto = new self();
        $dto->id = $layout->getId();
        $dto->uuid = $layout->getUuid();
        $dto->description = $layout->getDescription();
        $dto->contents = TranslatableContent::pending($layout, 'content');
        $dto->dates = $layout->getDates();

        return $dto;
    }
}
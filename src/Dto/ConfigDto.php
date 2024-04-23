<?php
/**
 * @author Manuel Aguirre
 */

declare(strict_types=1);

namespace Optime\Email\Bundle\Dto;

use Optime\Email\Bundle\Constraints\TwigContent;
use Optime\Email\Bundle\Entity\EmailLayout;
use Optime\Email\Bundle\Entity\EmailMaster;
use Optime\Util\Entity\Embedded\Date;
use Optime\Util\Translation\TranslatableContent;
use Optime\Util\Translation\Validation\TranslatableConstraint;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * @author Manuel Aguirre
 */
class ConfigDto
{
    public ?Uuid $uuid = null;
    #[NotBlank]
    public ?string $code = null;
    #[NotBlank]
    public ?string $description = null;
    #[NotBlank]
    public ?Uuid $layoutUuid = null;
    #[NotBlank]
    public ?string $target = null;
    #[NotNull]
    public ?bool $editable = null;
    public ?Date $dates = null;

    public static function fromEntity(EmailMaster $config): self
    {
        $dto = new self();
        $dto->uuid = $config->getUuid();
        $dto->code = $config->getCode();
        $dto->description = $config->getDescription();
        $dto->layoutUuid = $config->getLayout()->getUuid();
        $dto->target = $config->getTarget();
        $dto->editable = $config->isEditable();
        $dto->dates = $config->getDates();

        return $dto;
    }
}
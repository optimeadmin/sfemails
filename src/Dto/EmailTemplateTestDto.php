<?php
/**
 * @author Manuel Aguirre
 */

declare(strict_types=1);

namespace Optime\Email\Bundle\Dto;

use Optime\Email\Bundle\Constraints\TwigContent;
use Optime\Email\Bundle\Entity\EmailTemplate;
use Optime\Email\Bundle\Service\Email\App\EmailAppProvider;
use Optime\Util\Entity\Embedded\Date;
use Optime\Util\Translation\TranslatableContent;
use Optime\Util\Translation\Validation\TranslatableConstraint;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * @author Manuel Aguirre
 */
class EmailTemplateTestDto
{
    public ?string $vars = null;
    #[Count(min: 1)]
    public ?array $emails = null;
}
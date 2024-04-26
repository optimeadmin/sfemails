<?php
/**
 * @author Manuel Aguirre
 */

declare(strict_types=1);

namespace Optime\Email\Bundle\Dto;

use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Manuel Aguirre
 */
class EmailTemplateTestDto
{
    #[NotBlank]
    public ?string $locale = null;
    public ?string $vars = null;
    #[Count(min: 1)]
    public ?array $emails = null;
}
<?php
/**
 * @author Manuel Aguirre
 */

declare(strict_types=1);

namespace Optime\Email\Bundle\Dto;

use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Manuel Aguirre
 */
class UuidsDto
{
    /**
     * @var Uuid[]
     */
    #[Count(min: 1)]
    public ?array $uuids = null;
}
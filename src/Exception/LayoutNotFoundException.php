<?php
/**
 * @author Manuel Aguirre
 */

declare(strict_types=1);

namespace Optime\Email\Bundle\Exception;

use LogicException;
use Optime\Util\Exception\ValidationException;

/**
 * @author Manuel Aguirre
 */
class LayoutNotFoundException extends LogicException implements InvalidValueErrorInterface
{
    public function toValidationException(): ValidationException
    {
        return ValidationException::create('Invalid Layout', 'layoutUuid');
    }
}
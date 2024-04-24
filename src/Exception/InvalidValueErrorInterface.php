<?php
/**
 * @author Manuel Aguirre
 */

declare(strict_types=1);

namespace Optime\Email\Bundle\Exception;

use Optime\Util\Exception\ValidationException;
use Throwable;

/**
 * @author Manuel Aguirre
 */
interface InvalidValueErrorInterface extends Throwable
{
    public function toValidationException(): ValidationException;
}
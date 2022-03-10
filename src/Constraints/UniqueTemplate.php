<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

/**
 * @author Manuel Aguirre
 */
#[Attribute(Attribute::TARGET_CLASS)]
class UniqueTemplate extends Constraint
{
    public string $message = 'This template already exists for emailCode "{emailCode}" in app "{app}".';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
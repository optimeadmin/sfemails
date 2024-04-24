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
    public function __construct(
        mixed $options = null,
        ?array $groups = null,
        mixed $payload = null,
        public ?string $errorPath = null,
        public string $message = 'This template already exists for emailCode "{emailCode}" in app "{app}".',
    ) {
        parent::__construct($options, $groups, $payload);
    }

    public function getTargets(): array|string
    {
        return self::CLASS_CONSTRAINT;
    }
}
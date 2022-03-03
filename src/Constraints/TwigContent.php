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
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD)]
class TwigContent extends Constraint
{
}
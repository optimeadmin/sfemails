<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Service\Template\Twig\Sandbox;

use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityPolicyInterface;
use function array_intersect;
use function count;
use function dd;

/**
 * @author Manuel Aguirre
 */
class SecurityPolicy implements SecurityPolicyInterface
{
    private const INVALID_TAGS = [
        'apply',
        'block',
        'cache',
        'deprecated',
        'do',
        'embed',
        'extends',
        'flush',
        'from',
        'import',
        'include',
        'macro',
        'sandbox',
        'use',
    ];

    public function checkSecurity($tags, $filters, $functions): void
    {
        $invalidTags = array_intersect($tags, self::INVALID_TAGS);

        if (0 !== count($invalidTags)) {
            throw new SecurityNotAllowedTagError(
                sprintf('Tag "%s" is not supported for emails content', $invalidTags[0]), $invalidTags[0]
            );
        }
    }

    public function checkMethodAllowed($obj, $method): void
    {
    }

    public function checkPropertyAllowed($obj, $method): void
    {
    }
}
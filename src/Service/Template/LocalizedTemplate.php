<?php
/**
 * @author Manuel Aguirre
 */

declare(strict_types=1);

namespace Optime\Email\Bundle\Service\Template;

/**
 * @author Manuel Aguirre
 */
class LocalizedTemplate
{
    public function __construct(
        public readonly string $subject,
        public readonly string $content,
        public readonly string $layout,
        public readonly string $locale,
    ) {
    }
}
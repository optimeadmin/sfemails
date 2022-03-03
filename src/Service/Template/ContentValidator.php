<?php

/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Service\Template;

use Throwable;

/**
 * @author Manuel Aguirre
 */
class ContentValidator
{
    public function __construct(
        private TemplateRenderer $renderer,
    ) {
    }

    public function validate(string $content, array $variables = []): ?Throwable
    {
        try {
            $this->renderer->renderContent($content, $variables);
        } catch (Throwable $exception) {
            return $exception;
        }

        return null;
    }
}
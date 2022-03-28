<?php

/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Service\Template;

use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;
use function dd;

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
            if ($exception instanceof RouteNotFoundException
                || $exception->getPrevious() instanceof RouteNotFoundException) {
                return null;
            }

            if ($exception instanceof InvalidParameterException
                || $exception->getPrevious() instanceof InvalidParameterException) {
                return null;
            }


            return $exception;
        }

        return null;
    }
}
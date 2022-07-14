<?php
/**
 * @author Manuel Aguirre
 */

declare(strict_types=1);

namespace Optime\Email\Bundle\Service\Template\Variable;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Manuel Aguirre
 */
class TemplateVarsNormalizer
{
    public function __construct(
        private TranslatorInterface $translator,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function normalize(array $variables): array
    {
        foreach ($variables as $index => &$variable) {
            if ($variable instanceof Url) {
                $variable = $variable->generate($this->urlGenerator);
            }
        }

        $locale = $this->resolveLocale($variables['_locale'] ?? null);
        $variables['_locale'] = $locale;

        return $variables;
    }

    private function resolveLocale(?string $locale): string
    {
        return $locale ?? $this->translator->getLocale();
    }
}
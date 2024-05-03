<?php
/**
 * @author Manuel Aguirre
 */

declare(strict_types=1);

namespace Optime\Email\Bundle\Service\Template\Variable;

use Optime\Email\Bundle\Entity\EmailLog;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;
use function rtrim;
use function sprintf;

/**
 * @author Manuel Aguirre
 */
class TemplateVarsNormalizer
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly RequestStack $requestStack,
    ) {
    }

    public function normalize(array $variables): array
    {
        foreach ($variables as &$variable) {
            if ($variable instanceof Url) {
                $variable = $variable->generate($this->urlGenerator);
            }
        }

        if (!isset($variables[EmailLog::UUID_VARIABLE])) {
            $variables[EmailLog::UUID_VARIABLE] = Uuid::v4();
        }

        $variables['_show_url'] = $this->getShowUrl($variables[EmailLog::UUID_VARIABLE]);

        $locale = $this->resolveLocale($variables['_locale'] ?? null);
        $variables['_locale'] = $locale;

        return $variables;
    }

    private function resolveLocale(?string $locale): string
    {
        return $locale ?? $this->translator->getLocale();
    }

    private function getShowUrl($uuid): string
    {
        return sprintf(
            '%s/email/show/%s',
            rtrim($this->requestStack->getMainRequest()->getSchemeAndHttpHost()),
            $uuid
        );
    }
}
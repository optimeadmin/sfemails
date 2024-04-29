<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Service\Template;

use Optime\Email\Bundle\Entity\EmailLayout;
use Optime\Email\Bundle\Entity\EmailTemplate;
use Symfony\Contracts\Translation\LocaleAwareInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Twig\TemplateWrapper;

/**
 * @author Manuel Aguirre
 */
class TemplateRenderer
{
    public function __construct(
        private readonly Environment $twig,
        private readonly ArrayLoader $twigLoader,
        private readonly TranslatorInterface $translator,
        private readonly LocalizedTemplateProvider $localizedTemplateProvider,
    ) {
    }

    public function renderContent(string $content, array $variables = []): string
    {
        $templateName = 'template';
        $this->twigLoader->setTemplate($templateName, $content);

        return $this->doRender($templateName, $variables);
    }

    public function renderLayout(EmailLayout $layout, array $variables = []): string
    {
        return $this->renderContent($layout, $variables);
    }

    public function render(EmailTemplate $template, array $variables = []): RenderedTemplate
    {
        $locale = $this->getLocale($variables);
        $localizedTemplate = $this->localizedTemplateProvider->get($template, $locale);

        $subject = $this->renderContent($localizedTemplate->subject, $variables);
        $templateContent = $this->renderContent($localizedTemplate->content, $variables);
        $allContent = $this->renderContent(
            $localizedTemplate->layout,
            [...$variables, 'content' => $templateContent]
        );

        return new RenderedTemplate($subject, $allContent, $templateContent);
    }

    private function doRender(string|TemplateWrapper $template, array $vars): string
    {
        $originalLocale = $this->setLocaleIfApply($vars['_locale'] ?? null);
        $content = $this->twig->render($template, $vars);
        $this->restoreLocaleIfApply($originalLocale);

        return $content;
    }

    private function setLocaleIfApply(?string $locale): string
    {
        $originalLocale = $this->translator->getLocale();
        if (null == $locale || $locale === $originalLocale) {
            return $originalLocale;
        }

        if ($this->translator instanceof LocaleAwareInterface) {
            $this->translator->setLocale($locale);
        }

        return $originalLocale;
    }

    private function getLocale(array $templateVars): string
    {
        return $templateVars['_locale'] ?? $this->translator->getLocale();
    }

    private function restoreLocaleIfApply(string $locale): void
    {
        if ($this->translator instanceof LocaleAwareInterface) {
            $this->translator->setLocale($locale);
        }
    }
}
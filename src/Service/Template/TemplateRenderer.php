<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Service\Template;

use Optime\Email\Bundle\Entity\EmailLayout;
use Optime\Email\Bundle\Entity\EmailTemplate;
use Symfony\Component\Translation\LocaleSwitcher;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
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
        private readonly LocaleSwitcher $localeSwitcher,
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

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    private function doRender(string|TemplateWrapper $template, array $vars): string
    {
        $locale = $this->getLocale($vars);
        return $this->localeSwitcher->runWithLocale($locale, function () use ($template, $vars) {
            return $this->twig->render($template, $vars);
        });
    }

    private function getLocale(array $templateVars): string
    {
        return $templateVars['_locale'] ?? $this->localeSwitcher->getLocale();
    }
}
<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Service\Template;

use Optime\Email\Bundle\Entity\EmailLayout;
use Optime\Email\Bundle\Entity\EmailTemplate;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

/**
 * @author Manuel Aguirre
 */
class TemplateRenderer
{
    public function __construct(
        private Environment $twig,
        private ArrayLoader $twigLoader,
    ) {
    }

    public function renderContent(string $content, array $variables = []): string
    {
        $templateName = 'template';
        $this->twigLoader->setTemplate($templateName, $content);

        return $this->twig->render($templateName, $variables);
    }

    public function renderLayout(EmailLayout $layout, array $variables = []): string
    {
        return $this->renderContent($layout, $variables);
    }

    public function render(EmailTemplate $template, array $variables = [], bool $withLayout = true): string
    {
        $content = $this->renderContent($template, $variables);

        if (!$withLayout) {
            return $content;
        }

        return $this->renderLayout(
            $template->getLayout(),
            [...$variables, 'content' => $content],
        );
    }
}
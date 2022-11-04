<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Service\Email;

use Optime\Email\Bundle\Entity\EmailTemplate;
use Optime\Email\Bundle\Service\Template\TemplateRenderer;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use function strip_tags;

/**
 * @author Manuel Aguirre
 */
class EmailFactory
{
    public function __construct(
        private TemplateRenderer $renderer,
    ) {
    }

    public function fromTemplate(
        EmailTemplate $template,
        array $templateData,
    ): Email {
        $app = $template->getApp();

        return (new Email())
            ->from(new Address($app->getFromEmail(), $app->getFromName()))
            ->subject($this->renderSubject($template, $templateData))
            ->text($this->renderText($template, $templateData))
            ->html($this->renderHtml($template, $templateData));
    }

    private function renderHtml(EmailTemplate $template, array $templateContext): string
    {
        return $this->renderer->render($template, $templateContext);
    }

    private function renderText(EmailTemplate $template, array $templateContext): string
    {
        return strip_tags($this->renderer->render($template, $templateContext, false));
    }

    private function renderSubject(EmailTemplate $template, array $templateContext): string
    {
        return strip_tags($this->renderer->renderContent(
            $template->getSubject(),
            $templateContext
        ));
    }
}
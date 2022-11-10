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

        $templateContent = $this->renderer->render($template, $templateData);

        return (new Email())
            ->from(new Address($app->getFromEmail(), $app->getFromName()))
            ->subject($templateContent->getSubject())
            ->text($templateContent->rawContent())
            ->html($templateContent->getContent());
    }
}
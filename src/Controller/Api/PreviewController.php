<?php
/**
 * @author Manuel Aguirre
 */

declare(strict_types=1);

namespace Optime\Email\Bundle\Controller\Api;

use Optime\Email\Bundle\Entity\EmailLayout;
use Optime\Email\Bundle\Entity\EmailTemplate;
use Optime\Email\Bundle\Service\Template\TemplateRenderer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @author Manuel Aguirre
 */
#[Route('/api/preview')]
class PreviewController
{
    public function __construct(
        private readonly TemplateRenderer $renderer,
    ) {
    }

    #[Route("/layout/{uuid}", methods: 'get')]
    public function layout(EmailLayout $layout): Response
    {
        return new Response($this->renderer->renderLayout($layout));
    }

    #[Route("/template/{uuid}", methods: 'get')]
    public function template(EmailTemplate $emailTemplate): Response
    {
        return new Response((string)$this->renderer->render($emailTemplate));
    }
}
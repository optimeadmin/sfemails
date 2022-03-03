<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Controller;

use Optime\Email\Bundle\Entity\EmailLayout;
use Optime\Email\Bundle\Entity\EmailTemplate;
use Optime\Email\Bundle\Service\Template\TemplateRenderer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Manuel Aguirre
 */
#[Route("/config/preview")]
class PreviewController extends AbstractController
{
    public function __construct(
        private TemplateRenderer $renderer,
    ) {
    }

    #[Route("/layout/{uuid}/", name: "optime_emails_layout_preview")]
    public function index(EmailLayout $layout): Response
    {
        return $this->render('@OptimeEmail/preview/preview.html.twig', [
            'content' => $this->renderer->renderLayout($layout),
        ]);
    }

    #[Route("/template/{uuid}/", name: "optime_emails_template_preview")]
    public function template(EmailTemplate $template): Response
    {
        return $this->render('@OptimeEmail/preview/preview.html.twig', [
            'content' => $this->renderer->render($template),
        ]);
    }
}
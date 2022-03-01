<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Controller;

use Optime\Email\Bundle\Entity\EmailLayout;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Manuel Aguirre
 */
#[Route("/config/preview")]
class PreviewController extends AbstractController
{
    #[Route("/layout/{uuid}/", name: "optime_emails_layout_preview")]
    public function index(EmailLayout $layout): Response
    {
        return $this->render('@OptimeEmail/preview/preview.html.twig', [
            'content' => $layout->getContent(),
        ]);
    }
}
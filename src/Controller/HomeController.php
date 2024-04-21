<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Manuel Aguirre
 */
#[Route("/home")]
class HomeController extends AbstractController
{
    #[Route("/{path<.*>?}", name: "optime_emails_home")]
    public function index(): Response
    {
        return $this->render('@OptimeEmail/home.html.twig', [
            'items' => [],
        ]);
    }
}
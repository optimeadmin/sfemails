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
#[Route("/config")]
class EmailMasterController extends AbstractController
{
    #[Route("/")]
    public function index(): Response
    {
        return $this->render('@OptimeEmail/email_master/index.html.twig', [

        ]);
    }
}
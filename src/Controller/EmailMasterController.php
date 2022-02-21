<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Controller;

use Optime\Email\Bundle\Form\Type\EmailMasterFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Manuel Aguirre
 */
#[Route("/config")]
class EmailMasterController extends AbstractController
{
    #[Route("/", name: "optime_emails_config_list")]
    public function index(): Response
    {
        return $this->render('@OptimeEmail/email_master/index.html.twig', [

        ]);
    }

    #[Route("/create", name: "optime_emails_config_create")]
    public function create(Request $request): Response
    {
        $form = $this->createForm(EmailMasterFormType::class);
        $form->handleRequest($request);

        return $this->render('@OptimeEmail/email_master/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
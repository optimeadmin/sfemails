<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Optime\Email\Bundle\Entity\EmailMaster;
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
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[Route("/", name: "optime_emails_config_list")]
    public function index(): Response
    {
        return $this->render('@OptimeEmail/email_master/index.html.twig', [

        ]);
    }

    #[Route("/create", name: "optime_emails_config_create")]
    public function create(Request $request): Response
    {
        $form = $this->createForm(EmailMasterFormType::class, new EmailMaster());
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $this->entityManager->persist($form->getData());
            $this->entityManager->flush();

            return $this->redirectToRoute('optime_emails_config_list');
        }

        return $this->render('@OptimeEmail/email_master/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
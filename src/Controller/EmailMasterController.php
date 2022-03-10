<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Optime\Email\Bundle\Entity\EmailMaster;
use Optime\Email\Bundle\Form\Type\EmailMasterFormType;
use Optime\Email\Bundle\Repository\EmailMasterRepository;
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
    public function index(EmailMasterRepository $repository): Response
    {
        $items = $repository->findAll();

        return $this->render('@OptimeEmail/email_master/index.html.twig', [
            'items' => $items,
        ]);
    }

    #[Route("/create", name: "optime_emails_config_create")]
    public function create(Request $request): Response
    {
        $config = new EmailMaster();
        $form = $this->createForm(EmailMasterFormType::class, $config);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $this->entityManager->persist($config);
            $this->entityManager->flush();

            $this->addFlash('success', 'Item created successfully.');

            return $this->redirectToRoute('optime_emails_config_edit', [
                'uuid' => $config->getUuid(),
            ]);
        }

        return $this->render('@OptimeEmail/email_master/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/edit/{uuid}/", name: "optime_emails_config_edit")]
    public function edit(Request $request, EmailMaster $email): Response
    {
        $form = $this->createForm(EmailMasterFormType::class, $email);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $this->entityManager->persist($email);
            $this->entityManager->flush();

            $this->addFlash('success', 'Item edited successfully.');

            return $this->redirectToRoute('optime_emails_config_edit', [
                'uuid' => $email->getUuid(),
            ]);
        }

        return $this->render('@OptimeEmail/email_master/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
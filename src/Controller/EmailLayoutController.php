<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Optime\Email\Bundle\Entity\EmailLayout;
use Optime\Email\Bundle\Form\Type\EmailLayoutFormType;
use Optime\Email\Bundle\Repository\EmailLayoutRepository;
use Optime\Util\Http\Controller\HandleAjaxForm;
use Optime\Util\Http\Controller\PartialAjaxView;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Manuel Aguirre
 */
#[Route("/config/layout")]
class EmailLayoutController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[Route("/", name: "optime_emails_layout_list")]
    public function index(EmailLayoutRepository $repository): Response
    {
        $items = $repository->findAll();

        return $this->render('@OptimeEmail/email_layout/index.html.twig', [
            'items' => $items,
        ]);
    }

    #[Route("/create", name: "optime_emails_layout_create")]
    #[HandleAjaxForm]
    #[PartialAjaxView]
    public function create(Request $request): Response
    {
        $form = $this->createForm(EmailLayoutFormType::class, new EmailLayout(), [
            'action' => $request->getRequestUri()
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $this->entityManager->persist($form->getData());
            $this->entityManager->flush();

            return $this->redirectToRoute('optime_emails_layout_list');
        }

        return $this->render('@OptimeEmail/email_layout/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/edit/{uuid}/", name: "optime_emails_layout_edit")]
    public function edit(
        Request $request,
        EmailLayout $layout,
    ): Response {
        $form = $this->createForm(EmailLayoutFormType::class, $layout);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $this->entityManager->persist($layout);
            $this->entityManager->flush();

            return $this->redirectToRoute('optime_emails_layout_list');
        }

        return $this->render('@OptimeEmail/email_layout/form.html.twig', [
            'form' => $form->createView(),
            'layout' => $layout,
        ]);
    }
}
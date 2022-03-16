<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Optime\Email\Bundle\Entity\EmailApp;
use Optime\Email\Bundle\Repository\EmailAppRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

/**
 * @author Manuel Aguirre
 */
#[Route("/config/app")]
class EmailAppController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private EmailAppRepository $appRepository,
    ) {
    }

    #[Route("/create-default/", name: "optime_emails_app_create_default")]
    public function createDefault(): Response
    {
        if (!$this->appRepository->isEmpty()) {
            $this->addFlash('warning', 'The app data is not empty');
        } else {
            try {
                $app = new EmailApp();
                $this->entityManager->persist($app);
                $this->entityManager->flush();

                $this->addFlash('success', 'App created successfully');
            } catch (Throwable $exception) {
                $this->addFlash('danger', 'Error: ' . $exception->getMessage());
            }
        }

        return $this->redirectToRoute('optime_emails_template_create');
    }
}
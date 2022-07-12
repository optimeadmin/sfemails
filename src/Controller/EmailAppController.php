<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Optime\Email\Bundle\Entity\EmailApp;
use Optime\Email\Bundle\Service\Email\App\EmailAppProvider;
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
        private EmailAppProvider $emailAppProvider,
    ) {
    }

    #[Route("/create-default/", name: "optime_emails_app_create_default")]
    public function createDefault(): Response
    {
        if (!0 < $this->emailAppProvider->count()) {
            $this->addFlash('warning', 'The app data is not empty');
        } else {
            try {
                $app = $this->emailAppProvider->tryNewInstance();

                if ($app) {
                    $this->entityManager->persist($app);
                    $this->entityManager->flush();
                    $this->addFlash('success', 'App created successfully');
                } else {
                    $this->addFlash('warning', 'The app cannot be created');
                }
            } catch (Throwable $exception) {
                $this->addFlash('danger', 'Error: ' . $exception->getMessage());
            }
        }

        return $this->redirectToRoute('optime_emails_template_create');
    }
}
<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Controller;

use BadMethodCallException;
use Optime\Util\Translation\LocalesProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Manuel Aguirre
 */
class HomeController extends AbstractController
{
    #[Route("/home/{path<.*>?}", name: "optime_emails_home")]
    public function index(LocalesProviderInterface $localesProvider): Response
    {
        return $this->render('@OptimeEmail/home.html.twig', [
            'items' => [],
            'locales' => $localesProvider->getLocales(),
        ]);
    }

    #[Route("/api", name: 'optime_emails_api_url')]
    public function apiUrl(): never
    {
        throw new BadMethodCallException("Invalid Url");
    }
}
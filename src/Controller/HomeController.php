<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Controller;

use BadMethodCallException;
use Optime\Email\Bundle\Service\Email\App\EmailAppProvider;
use Optime\Util\Translation\LocalesProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Manuel Aguirre
 */
class HomeController extends AbstractController
{
    public function __construct(private readonly EmailAppProvider $appProvider)
    {
    }

    #[Route("/home/{path<.*>?}", name: "optime_emails_home")]
    public function index(LocalesProviderInterface $localesProvider): Response
    {
        $appsCount = $this->appProvider->count();

        return $this->render('@OptimeEmail/home.html.twig', [
            'items' => [],
            'locales' => $localesProvider->getLocales(),
            'appsCount' => $appsCount,
        ]);
    }

    #[Route("/api", name: 'optime_emails_api_url')]
    public function apiUrl(): never
    {
        throw new BadMethodCallException("Invalid Url");
    }
}
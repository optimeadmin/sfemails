<?php
/**
 * @author Manuel Aguirre
 */

declare(strict_types=1);

namespace Optime\Email\Bundle\Controller\Api;

use Optime\Email\Bundle\Dto\EmailLayoutDto;
use Optime\Email\Bundle\Entity\EmailLayout;
use Optime\Email\Bundle\Repository\EmailLayoutRepository;
use Optime\Email\Bundle\Service\Email\Layout\DefaultLayoutCreator;
use Optime\Email\Bundle\Service\Template\TemplateRenderer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @author Manuel Aguirre
 */
#[Route('/api/layouts', name: 'optime_emails_api_layout_')]
class LayoutController extends AbstractController
{
    public function __construct(
        private readonly EmailLayoutRepository $repository,
        private readonly DefaultLayoutCreator $defaultLayoutCreator,
    ) {
    }

    #[Route('', name: 'get_all', methods: 'get')]
    public function getAll(): JsonResponse
    {
        $this->defaultLayoutCreator->createIfApply();

        $items = $this->repository->findAll();

        return $this->json(array_map(EmailLayoutDto::fromEntity(...), $items));
    }
}
<?php
/**
 * @author Manuel Aguirre
 */

declare(strict_types=1);

namespace Optime\Email\Bundle\Controller\Api;

use Knp\Component\Pager\Pagination\SlidingPagination;
use Knp\Component\Pager\PaginatorInterface;
use Optime\Email\Bundle\Entity\EmailLog;
use Optime\Email\Bundle\Repository\EmailLogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @author Manuel Aguirre
 */
#[Route('/api/logs')]
class LogController extends AbstractController
{
    public function __construct(
        private readonly EmailLogRepository $repository,
        private readonly PaginatorInterface $paginator,
    ) {
    }

    #[Route('', methods: 'get')]
    public function getAll(Request $request): JsonResponse
    {
        /** @var SlidingPagination $items */
        $items = $this->paginator->paginate(
            $this->repository->queryAll([]),
            $request->query->getInt('page', 1),
            $request->query->getInt('perPage', 100),
        );

        $mappedItems = [];

        /** @var EmailLog $item */
        foreach ($items as $item) {
            $mappedItems[] = $item->toArray();
        }

        $data = [
            'paginationData' => $items->getPaginationData(),
            'data' => $mappedItems,
        ];

        return $this->json($data);
    }
}
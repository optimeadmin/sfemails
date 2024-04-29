<?php
/**
 * @author Manuel Aguirre
 */

declare(strict_types=1);

namespace Optime\Email\Bundle\Controller\Api;

use Knp\Component\Pager\Pagination\SlidingPagination;
use Knp\Component\Pager\PaginatorInterface;
use Optime\Email\Bundle\Dto\UuidsDto;
use Optime\Email\Bundle\Entity\EmailLog;
use Optime\Email\Bundle\Repository\EmailLogRepository;
use Optime\Email\Bundle\Service\Email\MailerLog;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use function dump;

/**
 * @author Manuel Aguirre
 */
#[Route('/api/logs')]
class LogController extends AbstractController
{
    public function __construct(
        private readonly EmailLogRepository $repository,
        private readonly PaginatorInterface $paginator,
        private readonly MailerLog $mailerLog,
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

    #[Route('/{uuid}', methods: 'get')]
    public function getContent(EmailLog $emailLog): Response
    {
        return new Response($emailLog->getContent());
    }

    #[Route("", methods: 'post')]
    public function resend(#[MapRequestPayload] UuidsDto $uuids): Response
    {
        $error = false;

        foreach ($uuids->uuids as $uuid) {
            if ($log = $this->repository->byUuid($uuid)) {
                if (!$this->mailerLog->resend($log)) {
                    $error = true;
                }
            }
        }
        dump($uuids);
//        if ($mailer->resend($emailLog)) {
//            $this->addFlash('success', "Success Resend '{$emailLog->getUuid()}'!");
//        } else {
//            $this->addFlash('danger', "Failed Resend '{$emailLog->getUuid()}'!");
//        }

        return $this->json($uuids, $error ? Response::HTTP_MULTI_STATUS : 200);
    }
}
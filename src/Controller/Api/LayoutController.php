<?php
/**
 * @author Manuel Aguirre
 */

declare(strict_types=1);

namespace Optime\Email\Bundle\Controller\Api;

use Doctrine\ORM\EntityManagerInterface;
use Optime\Email\Bundle\Dto\EmailLayoutDto;
use Optime\Email\Bundle\Entity\EmailLayout;
use Optime\Email\Bundle\Repository\EmailLayoutRepository;
use Optime\Email\Bundle\Service\Email\Layout\DefaultLayoutCreator;
use Optime\Util\Translation\Persister\TranslatableContentPersister;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @author Manuel Aguirre
 */
#[Route('/api/layouts')]
class LayoutController extends AbstractController
{
    public function __construct(
        private readonly EmailLayoutRepository $repository,
        private readonly DefaultLayoutCreator $defaultLayoutCreator,
        private readonly EntityManagerInterface $entityManager,
        private readonly TranslatableContentPersister $contentPersister,
    ) {
    }

    #[Route('', methods: 'get')]
    public function getAll(): JsonResponse
    {
        $this->defaultLayoutCreator->createIfApply();

        $items = $this->repository->findAll();

        return $this->json(array_map(EmailLayoutDto::fromEntity(...), $items));
    }

    #[Route('', methods: 'post')]
    public function create(#[MapRequestPayload] EmailLayoutDto $dto): JsonResponse
    {
        $layout = EmailLayout::create($dto);
        $this->entityManager->persist($layout);

        $this->contentPersister->prepare($layout)->persist('content', $dto->content);
        $this->entityManager->flush();

        return $this->json(EmailLayoutDto::fromEntity($layout));
    }
}
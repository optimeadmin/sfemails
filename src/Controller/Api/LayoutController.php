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
use Optime\Util\Translation\Translation;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
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
        private readonly Translation $translation,
    ) {
    }

    #[Route('', methods: 'get')]
    public function getAll(): JsonResponse
    {
        $this->defaultLayoutCreator->createIfApply();

        $items = $this->repository->findAll();

        return $this->json(array_map(EmailLayoutDto::fromEntity(...), $items));
    }

    #[Route('/{uuid}', methods: 'get')]
    public function getOneByUuid(#[MapEntity] EmailLayout $layout): JsonResponse
    {
        return $this->json(EmailLayoutDto::fromEntity($layout));
    }

    #[Route('', methods: 'post')]
    public function create(#[MapRequestPayload] EmailLayoutDto $dto): JsonResponse
    {
        $layout = EmailLayout::create($dto);
        $this->entityManager->persist($layout);

        $this->translation->preparePersist($layout)->persist('content', $dto->content);
        $this->entityManager->flush();

        return $this->json(EmailLayoutDto::fromEntity($layout));
    }

    #[Route('/{uuid}', methods: 'patch')]
    public function update(
        #[MapEntity] EmailLayout $layout,
        #[MapRequestPayload] EmailLayoutDto $dto
    ): JsonResponse {
        $this->translation->refreshInDefaultLocale($layout);
        $layout->update($dto);
        $this->entityManager->persist($layout);

        $this->translation->preparePersist($layout)->persist('content', $dto->content);
        $this->entityManager->flush();

        return $this->json(EmailLayoutDto::fromEntity($layout));
    }
}
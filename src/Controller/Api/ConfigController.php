<?php
/**
 * @author Manuel Aguirre
 */

declare(strict_types=1);

namespace Optime\Email\Bundle\Controller\Api;

use Doctrine\ORM\EntityManagerInterface;
use Optime\Email\Bundle\Dto\ConfigDto;
use Optime\Email\Bundle\Dto\EmailLayoutDto;
use Optime\Email\Bundle\Entity\EmailLayout;
use Optime\Email\Bundle\Entity\EmailMaster;
use Optime\Email\Bundle\Exception\LayoutNotFoundException;
use Optime\Email\Bundle\Repository\EmailLayoutRepository;
use Optime\Email\Bundle\Repository\EmailMasterRepository;
use Optime\Util\Exception\ValidationException;
use Optime\Util\Translation\Translation;
use Optime\Util\Validator\DomainValidator;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @author Manuel Aguirre
 */
#[Route('/api/configs')]
class ConfigController extends AbstractController
{
    public function __construct(
        private readonly EmailMasterRepository $repository,
        private readonly EmailLayoutRepository $layoutRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly Translation $translation,
        private readonly DomainValidator $validator,
    ) {
    }

    #[Route('', methods: 'get')]
    public function getAll(): JsonResponse
    {
        $items = $this->repository->findAll();

        return $this->json(array_map(ConfigDto::fromEntity(...), $items));
    }

    #[Route('/{uuid}', methods: 'get')]
    public function getOneByUuid(#[MapEntity] EmailLayout $layout): JsonResponse
    {
        return $this->json(EmailLayoutDto::fromEntity($layout));
    }

    #[Route('', methods: 'post')]
    public function create(#[MapRequestPayload] ConfigDto $dto): JsonResponse
    {
        try {
            $config = EmailMaster::create($dto, $this->layoutRepository->byUuid($dto->layoutUuid));
            $this->validator->handle($config);
            $this->entityManager->persist($config);
            $this->entityManager->flush();
        } catch (LayoutNotFoundException) {
            return $this->json(ValidationException::create('Invalid Layout', 'layoutUuid'), 422);
        } catch (ValidationException $e) {
            return $this->json($e->getErrors(), 422);
        }

        return $this->json(ConfigDto::fromEntity($config));
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
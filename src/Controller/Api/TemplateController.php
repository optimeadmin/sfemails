<?php
/**
 * @author Manuel Aguirre
 */

declare(strict_types=1);

namespace Optime\Email\Bundle\Controller\Api;

use Optime\Email\Bundle\Dto\EmailTemplateDto;
use Optime\Email\Bundle\Dto\Factory\EmailTemplateDtoFactory;
use Optime\Email\Bundle\Entity\EmailTemplate;
use Optime\Email\Bundle\Repository\EmailTemplateRepository;
use Optime\Email\Bundle\Service\Template\UseCase\PersistEmailTemplateUseCase;
use Optime\Util\Exception\ValidationException;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @author Manuel Aguirre
 */
#[Route('/api/templates')]
class TemplateController extends AbstractController
{
    public function __construct(
        private readonly EmailTemplateRepository $repository,
        private readonly EmailTemplateDtoFactory $dtoFactory,
    ) {
    }

    #[Route('', methods: 'get')]
    public function getAll(): JsonResponse
    {
        $items = $this->repository->findAll();

        return $this->json($this->dtoFactory->fromItems($items));
    }

    #[Route('/{uuid}', methods: 'get')]
    public function getOneByUuid(#[MapEntity] EmailTemplate $emailTemplate): JsonResponse
    {
        return $this->json($this->dtoFactory->create($emailTemplate));
    }

    #[Route('', methods: 'post')]
    public function create(
        #[MapRequestPayload] EmailTemplateDto $dto,
        PersistEmailTemplateUseCase $useCase
    ): JsonResponse {
        try {
            $emailTemplate = $useCase->create($dto);
        } catch (ValidationException $e) {
            return $this->json($e->getErrors(), 422);
        }

        return $this->json($this->dtoFactory->create($emailTemplate));
    }

    #[Route('/{uuid}', methods: 'patch')]
    public function update(
        #[MapEntity] EmailTemplate $emailTemplate,
        #[MapRequestPayload] EmailTemplateDto $dto,
        PersistEmailTemplateUseCase $useCase,
    ): JsonResponse {
        try {
            $useCase->update($emailTemplate, $dto);
        } catch (ValidationException $e) {
            return $this->json($e->getErrors(), 422);
        }

        return $this->json($this->dtoFactory->create($emailTemplate));
    }
}
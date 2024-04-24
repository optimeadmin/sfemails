<?php
/**
 * @author Manuel Aguirre
 */

declare(strict_types=1);

namespace Optime\Email\Bundle\Controller\Api;

use Doctrine\ORM\EntityManagerInterface;
use Optime\Email\Bundle\Dto\ConfigDto;
use Optime\Email\Bundle\Dto\EmailTemplateDto;
use Optime\Email\Bundle\Entity\EmailMaster;
use Optime\Email\Bundle\Entity\EmailTemplate;
use Optime\Email\Bundle\Exception\InvalidValueErrorInterface;
use Optime\Email\Bundle\Exception\LayoutNotFoundException;
use Optime\Email\Bundle\Repository\EmailLayoutRepository;
use Optime\Email\Bundle\Repository\EmailMasterRepository;
use Optime\Email\Bundle\Repository\EmailTemplateRepository;
use Optime\Email\Bundle\Service\Email\App\EmailAppProvider;
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
#[Route('/api/templates')]
class TemplateController extends AbstractController
{
    public function __construct(
        private readonly EmailTemplateRepository $repository,
        private readonly EmailLayoutRepository $layoutRepository,
        private readonly EmailMasterRepository $configRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly DomainValidator $validator,
        private readonly EmailAppProvider $appProvider,
        private readonly Translation $translation,
    ) {
    }

    #[Route('', methods: 'get')]
    public function getAll(): JsonResponse
    {
        $items = $this->repository->findAll();

        return $this->json(array_map(EmailTemplateDto::fromEntity(...), $items));
    }

    #[Route('/{uuid}', methods: 'get')]
    public function getOneByUuid(#[MapEntity] EmailTemplate $emailTemplate): JsonResponse
    {
        return $this->json(EmailTemplateDto::fromEntity($emailTemplate));
    }

    #[Route('', methods: 'post')]
    public function create(#[MapRequestPayload] EmailTemplateDto $dto): JsonResponse
    {
        try {
            $app = $this->appProvider->getByIndex($dto->appId);
            $config = $this->configRepository->byUuid($dto->configUuid);
            $layout = $dto->layoutUuid ? $this->layoutRepository->byUuid($dto->layoutUuid) : null;

            $emailTemplate = EmailTemplate::create($dto, $app, $config, $layout);
            $this->validator->handle($emailTemplate);
            $this->entityManager->persist($emailTemplate);

            $persister = $this->translation->preparePersist($emailTemplate);
            $persister->persist('subject', $dto->subject);
            $persister->persist('content', $dto->content);

            $this->entityManager->flush();
        } catch (InvalidValueErrorInterface $e) {
            return $this->json($e->toValidationException(), 422);
        } catch (ValidationException $e) {
            return $this->json($e->getErrors(), 422);
        }

        return $this->json(EmailTemplateDto::fromEntity($emailTemplate));
    }

    #[Route('/{uuid}', methods: 'patch')]
    public function update(
        #[MapEntity] EmailMaster $config,
        #[MapRequestPayload] ConfigDto $dto
    ): JsonResponse {
        try {
            $config->update($dto, $this->layoutRepository->byUuid($dto->layoutUuid));
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
}
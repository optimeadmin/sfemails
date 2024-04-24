<?php
/**
 * @author Manuel Aguirre
 */

declare(strict_types=1);

namespace Optime\Email\Bundle\Service\Template\UseCase;

use Doctrine\ORM\EntityManagerInterface;
use Optime\Email\Bundle\Dto\EmailTemplateDto;
use Optime\Email\Bundle\Entity\EmailTemplate;
use Optime\Email\Bundle\Exception\InvalidValueErrorInterface;
use Optime\Email\Bundle\Repository\EmailLayoutRepository;
use Optime\Email\Bundle\Repository\EmailMasterRepository;
use Optime\Email\Bundle\Service\Email\App\EmailAppProvider;
use Optime\Util\Exception\ValidationException;
use Optime\Util\Translation\Translation;
use Optime\Util\Validator\DomainValidator;

/**
 * @author Manuel Aguirre
 */
class PersistEmailTemplateUseCase
{
    public function __construct(
        private readonly EmailAppProvider $appProvider,
        private readonly EmailMasterRepository $configRepository,
        private readonly EmailLayoutRepository $layoutRepository,
        private readonly DomainValidator $validator,
        private readonly EntityManagerInterface $entityManager,
        private readonly Translation $translation,
    ) {
    }

    /**
     * @throws ValidationException
     */
    public function create(EmailTemplateDto $dto): EmailTemplate
    {
        [$app, $config, $layout] = $this->getRelations($dto);

        $emailTemplate = EmailTemplate::create($dto, $app, $config, $layout);
        $this->validator->handle($emailTemplate);
        $this->entityManager->persist($emailTemplate);

        $persister = $this->translation->preparePersist($emailTemplate);
        $persister->persist('subject', $dto->subject);
        $persister->persist('content', $dto->content);

        $this->entityManager->flush();

        return $emailTemplate;
    }

    /**
     * @throws ValidationException
     */
    public function update(EmailTemplate $emailTemplate, EmailTemplateDto $dto): EmailTemplate
    {
        $this->translation->refreshInDefaultLocale($emailTemplate);

        [$app, $config, $layout] = $this->getRelations($dto);

        $emailTemplate->update($dto, $app, $config, $layout);
        $this->validator->handle($emailTemplate);
        $this->entityManager->persist($emailTemplate);

        $persister = $this->translation->preparePersist($emailTemplate);
        $persister->persist('subject', $dto->subject);
        $persister->persist('content', $dto->content);

        $this->entityManager->flush();

        return $emailTemplate;
    }

    private function getRelations(EmailTemplateDto $dto): array
    {
        try {
            $app = $this->appProvider->getByIndex($dto->appId);
            $config = $this->configRepository->byUuid($dto->configUuid);
            $layout = $dto->layoutUuid ? $this->layoutRepository->byUuid($dto->layoutUuid) : null;

            return [$app, $config, $layout];
        } catch (InvalidValueErrorInterface $error) {
            throw $error->toValidationException();
        }
    }
}
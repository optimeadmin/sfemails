<?php
/**
 * @author Manuel Aguirre
 */

declare(strict_types=1);

namespace Optime\Email\Bundle\Service\Email\App;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Optime\Email\Bundle\Entity\EmailAppInterface;
use Optime\Email\Bundle\Entity\EmailMaster;
use Optime\Email\Bundle\Exception\EmailAppNotFoundException;
use ReflectionClass;
use ReflectionException;
use function array_search;

/**
 * @author Manuel Aguirre
 */
class EmailAppProvider implements DefaultEmailAppResolverInterface
{
    private ?array $loadedApps = null;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function count(): int
    {
        return $this->getRepository()->count([]);
    }

    public function getDefaultIfApply(EmailMaster $config): ?EmailAppInterface
    {
        if (1 !== $this->count()) {
            return null;
        }

        return current($this->getRepository()->findAll());
    }

    public function tryNewInstance(): ?EmailAppInterface
    {
        $class = $this->getEmailAppClass();

        if (null === $class) {
            return null;
        }
        $reflection = new ReflectionClass($class);

        try {
            return $reflection->newInstance();
        } catch (ReflectionException $A) {
            return null;
        }
    }

    public function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(EmailAppInterface::class);
    }

    public function getByIndex(int|string $index): EmailAppInterface
    {
        $this->loadedApps ??= $this->getRepository()->findAll();

        if (!isset($this->loadedApps[$index])) {
            throw new EmailAppNotFoundException("No existe el indice '{$index}' en el array de apps");
        }

        return $this->loadedApps[$index];
    }

    public function getIndexByApp(EmailAppInterface $app): int
    {
        $this->loadedApps ??= $this->getRepository()->findAll();
        $index = array_search($app, $this->loadedApps);

        if (false === $index) {
            throw new EmailAppNotFoundException("No existe la app '{$app}' en el array de apps");
        }

        return (int)$index;
    }

    public function getEmailAppClass(): ?string
    {
        $metadata = $this->entityManager->getClassMetadata(EmailAppInterface::class);

        return $metadata->getName();
    }
}
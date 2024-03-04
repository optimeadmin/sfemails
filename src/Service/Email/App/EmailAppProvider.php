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
use ReflectionClass;
use ReflectionException;

/**
 * @author Manuel Aguirre
 */
class EmailAppProvider implements DefaultEmailAppResolverInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
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

    private function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(EmailAppInterface::class);
    }

    public function getEmailAppClass(): ?string
    {
        $metadata = $this->entityManager->getClassMetadata(EmailAppInterface::class);

        return $metadata->getName();
    }
}
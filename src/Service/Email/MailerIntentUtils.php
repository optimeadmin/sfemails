<?php
/**
 * @author Manuel Aguirre
 */

declare(strict_types=1);

namespace Optime\Email\Bundle\Service\Email;

use Doctrine\ORM\EntityManagerInterface;
use Optime\Email\Bundle\Entity\EmailLog;
use Optime\Email\Bundle\Service\Template\Variable\TemplateVarsNormalizer;
use Symfony\Bundle\SecurityBundle\Security;
use function method_exists;

/**
 * @author Manuel Aguirre
 */
class MailerIntentUtils
{
    private string|null|false $loggedUserIdentifier = false;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TemplateVarsNormalizer $varsNormalizer,
        private readonly ?Security $security,
    ) {
    }

    public function normalizeVariables(array $variables): array
    {
        return $this->varsNormalizer->normalize($variables);
    }

    public function getLoggedUserIdentifier(): ?string
    {
        if (false !== $this->loggedUserIdentifier) {
            return $this->loggedUserIdentifier;
        }

        if (!$user = $this->security?->getUser()) {
            return $this->loggedUserIdentifier = null;
        }

        return $this->loggedUserIdentifier = method_exists($user, 'getId')
            ? (string)$user->getId()
            : $user->getUserIdentifier();
    }

    public function saveLog(EmailLog $emailLog, bool $flush = true): void
    {
        $this->entityManager->persist($emailLog);
        $flush && $this->entityManager->flush();
    }
}
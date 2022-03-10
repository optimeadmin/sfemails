<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Constraints;

use Optime\Email\Bundle\Entity\EmailTemplate;
use Optime\Email\Bundle\Repository\EmailTemplateRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * @author Manuel Aguirre
 */
class UniqueTemplateValidator extends ConstraintValidator
{
    public function __construct(
        private EmailTemplateRepository $repository,
    ) {
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof UniqueTemplate) {
            throw new UnexpectedTypeException($constraint, UniqueTemplate::class);
        }

        if (null === $value) {
            return;
        }

        if (!$value instanceof EmailTemplate) {
            throw new UnexpectedValueException($value, EmailTemplate::class);
        }

        if (!$value->getConfig() || !$value->getApp()) {
            return;
        }

        $result = $this->repository->findOneBy([
            'config' => $value->getConfig(),
            'app' => $value->getApp(),
            'active' => true,
        ]);

        if (null === $result || $result === $value) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameters([
                '{emailCode}' => $value->getConfig()?->getCode(),
                '{app}' => (string)$value->getApp(),
            ])
            ->addViolation();
    }
}
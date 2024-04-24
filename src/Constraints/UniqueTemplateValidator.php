<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Constraints;

use Error;
use Optime\Email\Bundle\Entity\EmailTemplate;
use Optime\Email\Bundle\Repository\EmailTemplateRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use function str_contains;

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

        try {
            if (!$value->getConfig() || !$value->getApp()) {
                return;
            }
        } catch (Error $error) {
            if (str_contains($error->getMessage(), 'must not be accessed')) {
                return;
            } else {
                throw $error;
            }
        }

        $result = $this->repository->findOneBy([
            'config' => $value->getConfig(),
            'app' => $value->getApp(),
            'active' => true,
        ]);

        if (null === $result || $result === $value) {
            return;
        }

        $violation = $this->context->buildViolation($constraint->message)
            ->setParameters([
                '{emailCode}' => $value->getConfig()?->getCode(),
                '{app}' => (string)$value->getApp(),
            ]);

        if ($constraint->errorPath) {
            $violation->atPath($constraint->errorPath);
        }

        $violation->addViolation();
    }
}
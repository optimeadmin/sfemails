<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Constraints;

use Optime\Email\Bundle\Service\Template\ContentValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Twig\Error\Error;

/**
 * @author Manuel Aguirre
 */
class TwigContentValidator extends ConstraintValidator
{
    public function __construct(
        private ContentValidator $contentValidator,
    ) {
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof TwigContent) {
            throw new UnexpectedTypeException($constraint, TwigContent::class);
        }

        if (null == $value) {
            return;
        }

        if (!$error = $this->contentValidator->validate($value)) {
            return;
        }

        if ($error instanceof Error) {
            $errorLine = $error->getTemplateLine();
            $error = $error->getPrevious() ?? $error;
        } else {
            $errorLine = $error->getLine();
        }


        $this->context->buildViolation(sprintf(
            "Error: %s.\nLine: %s",
            $error->getMessage(),
            $errorLine,
        ))
            ->addViolation();
    }
}
<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ContainsAlphaNumericOnlyValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if(!$constraint instanceof ContainsAlphaNumericOnly){
            throw new UnexpectedTypeException($constraint, ContainsAlphaNumericOnly::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if(!is_string($value)){
            throw new UnexpectedValueException($value, 'string|int');
        }

        if(!preg_match('/^[a-zA-Z0-9]+$/', $value)){
            // TODO: implement the validation here
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }

    }
}

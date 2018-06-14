<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
final class HourGapValidator extends ConstraintValidator
{
    public function getTargets()
    {
        return Constraint::CLASS_CONSTRAINT;
    }

    public function validate($value, Constraint $constraint): void
    {
        $startTime = new DateTime($value['deliveryStartTime']);
        $endTime = new DateTime($value['deliveryEndTime']);

        $interval = datediff($startTime, $endTime);
        $hours = $interval->format('%h');

        if ($hours < 1 || $hours > 8) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
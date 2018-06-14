<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class HourGap extends Constraint
{
    public $message = 'The product must have the minimal properties required ("description", "price")';
}
<?php

namespace App\Validator;

use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class ContainsAlphaNumericOnly extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    //public $message = 'The value "{{ value }}" should only contains letters and numbers.';

    #[HasNamedArguments]
    public function __construct
    (
        public string $message = 'The value "{{ value }}" should only contains letters and numbers.', 
        array $groups = null, 
        mixed $payload = null
    ){
        parent::__construct([], $groups, $payload);

        $this->message = $message;
    }
}

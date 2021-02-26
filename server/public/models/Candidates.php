<?php

namespace App\Models;

use Phalcon\Mvc\Model;
use Phalcon\Messages\Message;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\InclusionIn;

class Candidates extends Model
{
    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'name',
            new Uniqueness(
                [
                    'field'   => 'name',
                    'message' => 'The candidate name must be unique',
                ]
            )
        );

        if ($this->age < 18) {
            $this->appendMessage(
                new Message('Candidate must be adult')
            );
        }

        // Validate the validator
        return $this->validate($validator);
    }
}

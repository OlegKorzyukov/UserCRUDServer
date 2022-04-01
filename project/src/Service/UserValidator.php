<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Exception\UserValidateException;

class UserValidator
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @throws UserValidateException
     */
    public function validateUser(User $user)
    {
        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            throw new UserValidateException((string) $errors);
        }
    }
}

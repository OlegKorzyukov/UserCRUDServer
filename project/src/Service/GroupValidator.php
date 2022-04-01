<?php

namespace App\Service;

use App\Exception\GroupValidateException;
use App\Entity\Group;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GroupValidator
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @throws GroupValidateException
     */
    public function validateGroup(Group $group)
    {
        $errors = $this->validator->validate($group);
        if (count($errors) > 0) {
            throw new GroupValidateException((string) $errors);
        }
    }
}

<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * @method User find($id, $lockMode = null, $lockVersion = null)
 * @method User[] findAll()
 */
class UserRepository extends EntityRepository
{

}

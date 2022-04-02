<?php

namespace App\Repository;

use App\Entity\Group;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityRepository;

/**
 * @method Group|null find($id, $lockMode = null, $lockVersion = null)
 * @method Group[] findAll()
 */
class GroupRepository extends EntityRepository
{
    /**
     * @throws Exception
     */
    public function findGroupsWithUsers(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = <<<SQL
                SELECT g.name as 'Group', group_concat(u.name) as 'Users' FROM user u JOIN users_groups gu
                    ON gu.user_id = u.id JOIN group_group g
                    ON g.id = gu.group_id
                GROUP BY g.id
        SQL;

        $stmt = $conn->prepare($sql);
        return $stmt->executeQuery()->fetchAllAssociative();
    }
}

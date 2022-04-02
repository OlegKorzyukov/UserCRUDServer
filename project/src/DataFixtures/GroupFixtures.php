<?php

namespace App\DataFixtures;

use App\Entity\Group;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GroupFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $group = new Group('group_' . $i);
            $this->addReference('group_' . $i, $group);
            $manager->persist($group);
        }
        $manager->flush();
    }
}

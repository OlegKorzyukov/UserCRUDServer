<?php

namespace App\DataFixtures;

use App\Entity\Group;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Faker\Factory;
use Faker\Generator;

class UserFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 50; $i++) {
            /** @var Group $group */
            $group = $this->getReference('group_' . rand(0, 9));

            $user = new User($this->faker->name, $this->faker->email);
            $user->addGroup($group);

            $this->addReference('user_' . $i, $user);
            $manager->persist($user);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [GroupFixtures::class];
    }
}

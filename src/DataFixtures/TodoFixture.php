<?php

namespace App\DataFixtures;

use App\Entity\Todo;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class TodoFixture extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $id = 1;
        $repo = $manager->getRepository(User::class);
        $user = $repo->find($id);
        for ($i = 1; $i < 20; $i++) {
            $todo = new Todo();
            $todo->setDescription('Lorem ipsum dolor sit amet consectetur, adipisicing elit. ');
            $todo->setCreatedAt(new \DateTime());
            $todo->setDueDate(new \DateTime());
            $todo->setUser($user);
            $manager->persist($todo);
        }
        $manager->flush();
    }
}

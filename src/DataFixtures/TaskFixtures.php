<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Task;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * Allow to create tasks fixtures
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $task = new Task();
            $task->setTitle('Ma tâche numéro : ' . $i);
            $task->setContent('Ceci est le contenu de ma tâche numéro : ' . $i . '.');
            $task->setCreatedAt(new DateTime());
            if ($i == 8) {
                $task->setAuthor($this->getReference(UserFixtures::USER_REFERENCE . '1'));
            }
            $manager->persist($task);
        }
        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [
            UserFixtures::class
        ];
    }
}

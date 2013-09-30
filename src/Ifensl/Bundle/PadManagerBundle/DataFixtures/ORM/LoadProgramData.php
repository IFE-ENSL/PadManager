<?php

namespace Ifensl\Bundle\PadManagerBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ifensl\Bundle\PadManagerBundle\Entity\Program;

class LoadProgramData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $program1 = new Program();
        $program1->setName("Programme 1");
        $manager->persist($program1);

        $program2 = new Program();
        $program2->setName("Programme 2");
        $manager->persist($program2);

        $manager->flush();
    }
}
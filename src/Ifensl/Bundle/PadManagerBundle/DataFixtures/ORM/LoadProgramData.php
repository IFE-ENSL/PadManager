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
        $program = new Program();
        $program->setName("Master Architecture de l'information");
        $manager->persist($program);

        $manager->flush();
    }
}
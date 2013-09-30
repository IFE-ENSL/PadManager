<?php

namespace Ifensl\Bundle\PadManagerBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ifensl\Bundle\PadManagerBundle\Entity\UE;

class LoadUEData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $informatique = new UE();
        $informatique->setName("Informatique");
        $manager->persist($informatique);

        $general = new UE();
        $general->setName("Général");
        $manager->persist($general);

        $manager->flush();
    }
}
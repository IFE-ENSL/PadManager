<?php

namespace Ifensl\Bundle\PadManagerBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ifensl\Bundle\PadManagerBundle\Entity\Unit;

class LoadUnitData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $informatique = new Unit();
        $informatique->setName("Informatique");
        $manager->persist($informatique);

        $general = new Unit();
        $general->setName("Général");
        $manager->persist($general);

        $manager->flush();
    }
}

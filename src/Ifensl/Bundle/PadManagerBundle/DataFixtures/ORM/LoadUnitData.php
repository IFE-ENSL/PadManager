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
        $INF01 = new Unit();
        $INF01->setName("INF01");
        $manager->persist($INF01);

        $INF02 = new Unit();
        $INF02->setName("INF02");
        $manager->persist($INF02);

        $INF03 = new Unit();
        $INF03->setName("INF03");
        $manager->persist($INF03);

        $INF04 = new Unit();
        $INF04->setName("INF04");
        $manager->persist($INF04);

        $INF05 = new Unit();
        $INF05->setName("INF05");
        $manager->persist($INF05);

        $INF06 = new Unit();
        $INF06->setName("INF06");
        $manager->persist($INF06);

        $INF14 = new Unit();
        $INF14->setName("INF14");
        $manager->persist($INF14);

        $INF24 = new Unit();
        $INF24->setName("INF24");
        $manager->persist($INF24);

        $manager->flush();
    }
}

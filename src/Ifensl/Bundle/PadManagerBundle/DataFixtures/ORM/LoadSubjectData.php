<?php

namespace Ifensl\Bundle\PadManagerBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ifensl\Bundle\PadManagerBundle\Entity\Subject;

class LoadSubjectData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $algo = new Subject();
        $algo->setName("Algorithmique");
        $manager->persist($algo);

        $gestion = new Subject();
        $gestion->setName("Gestion");
        $manager->persist($gestion);

        $algebre = new Subject();
        $algebre->setName("Algèbre");
        $manager->persist($algebre);

        $prisDeNote = new Subject();
        $prisDeNote->setName("Prise de notes");
        $manager->persist($prisDeNote);

        
        $manager->flush();
    }
}
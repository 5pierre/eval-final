<?php

namespace App\DataFixtures;

use App\Entity\Ecurie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EcurieFixtures extends Fixture
{
    // ALTER TABLE `ecurie` AUTO_INCREMENT=1;

    public function load(ObjectManager $manager): void
    {
        $ecuries = [
            [
                'nom'     => 'Mercedes-AMG Petronas',
                'marque'  => 'Mercedes'
            ],
            [
                'nom'     => 'Scuderia Ferrari',
                'marque'  => 'Ferrari'
            ],
            [
                'nom'     => 'Red Bull Racing',
                'marque'  => 'Honda'
            ],
            [
                'nom'     => 'McLaren Racing',
                'marque'  => 'McLaren'
            ]
        ];

        foreach($ecuries as $ecurieInfos) {
            $ecurie = new Ecurie();
            $ecurie->setNom($ecurieInfos['nom']);
            $ecurie->setMarque($ecurieInfos['marque']);
            $manager->persist($ecurie);
        }

        $manager->flush();
    }
}
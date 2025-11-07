<?php

namespace App\DataFixtures;

use App\Entity\Pilote;
use App\Entity\Ecurie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PiloteFixtures extends Fixture implements DependentFixtureInterface
{
    // ALTER TABLE `pilote` AUTO_INCREMENT=1;

    public function load(ObjectManager $manager): void
    {
        $pilotes = [
            [
                'prenom'          => 'Lewis',
                'nom'             => 'Hamilton',
                'points_licence'  => 12,
                'poste'           => true,
                'ecurie_id'       => 1
            ],
            [
                'prenom'          => 'George',
                'nom'             => 'Russell',
                'poste'           => true,
                'points_licence'  => 12,
                'ecurie_id'       => 1
            ],
            [
                'prenom'          => 'Mick',
                'nom'             => 'Schumacher',
                'points_licence'  => 12,
                'poste'           => false,
                'ecurie_id'       => 1
            ],
            [
                'prenom'          => 'Charles',
                'nom'             => 'Leclerc',
                'points_licence'  => 12,
                'poste'           => true,
                'ecurie_id'       => 2
            ],
            [
                'prenom'          => 'Carlos',
                'nom'             => 'Sainz',
                'points_licence'  => 12,
                'poste'           => true,
                'ecurie_id'       => 2
            ],
            [
                'prenom'          => 'Antonio',
                'nom'             => 'Giovinazzi',
                'points_licence'  => 12,
                'poste'           => false,
                'ecurie_id'       => 2
            ],
            [
                'prenom'          => 'Max',
                'nom'             => 'Verstappen',
                'points_licence'  => 12,
                'poste'           => true,
                'ecurie_id'       => 3
            ],
            [
                'prenom'          => 'Sergio',
                'nom'             => 'Perez',
                'points_licence'  => 12,
                'poste'           => true,
                'ecurie_id'       => 3
            ],
            [
                'prenom'          => 'Daniel',
                'nom'             => 'Ricciardo',
                'points_licence'  => 12,
                'poste'           => false,
                'ecurie_id'       => 3
            ],
            [
                'prenom'          => 'Lando',
                'nom'             => 'Norris',
                'points_licence'  => 12,
                'poste'           => true,
                'ecurie_id'       => 4
            ],
            [
                'prenom'          => 'Oscar',
                'nom'             => 'Piastri',
                'points_licence'  => 12,
                'poste'           => true,
                'ecurie_id'       => 4
            ],
            [
                'prenom'          => 'Pato',
                'nom'             => 'Oward',
                'points_licence'  => 12,
                'poste'           => false,
                'ecurie_id'       => 4
            ]
        ];

        foreach($pilotes as $piloteInfos) {
            $ecurie = $manager->getRepository(Ecurie::class)->find($piloteInfos['ecurie_id']);
            
            if ($ecurie) {
                $pilote = new Pilote();
                $pilote->setPrenom($piloteInfos['prenom']);
                $pilote->setNom($piloteInfos['nom']);
                $pilote->setPointsLicence($piloteInfos['points_licence']);
                $pilote->setPoste($piloteInfos['poste']);
                $pilote->setEcurie($ecurie);
                $manager->persist($pilote);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            EcurieFixtures::class
        ];
    }
}
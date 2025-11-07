<?php

namespace App\EventListener;

use App\Entity\Infraction;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Psr\Log\LoggerInterface;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: Infraction::class)]
class InfractionManager
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function postPersist(Infraction $infraction, PostPersistEventArgs $event): void
    {
        $pilote = $infraction->getPilote();
        
        if ($pilote && $pilote->getPointsLicence() < 1) {
            $pilote->setPoste(false);
            $event->getObjectManager()->flush();

            $this->logger->info('Pilote suspendu', ['pilote_id' => $pilote->getId(), 'pilote_nom' => $pilote->getPrenom() . ' ' . $pilote->getNom(), 'points_licence' => $pilote->getPointsLicence()]);
        }
    }
}
<?php

// src/Controller/CategoryController.php
namespace App\Controller;

// ...
use App\Entity\Infraction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\PiloteRepository;
use App\Repository\EcurieRepository;

#[Route('/f1')]// a modifier
class InfractionController extends AbstractController
{
    #[Route('/createInfraction', name: 'create_infraction', methods: ['POST'])]
    public function createInfraction(
        Request $request,
        EntityManagerInterface $entityManager,
        PiloteRepository $piloteRepository,
        EcurieRepository $ecurieRepository
    ): JsonResponse
    {
        //$hasAccess = $this->isGranted('ROLE_ADMIN');

        //if ($hasAccess) {
            $content = json_decode($request->getContent(), true);

            $infraction = new Infraction();

            if (!isset($content['piloteId']) && !isset($content['ecurieId'])) {
                return $this->json(['message' => 'Champ piloteId ou ecurieId manquant'], JsonResponse::HTTP_BAD_REQUEST);
            }

            $infraction->setPenalite($content['penalite']);
            $infraction->setAmende($content['amende']);
            $infraction->setCourse($content['course']);
            $infraction->setDate(new \DateTime($content['date']));
            $infraction->setDescription($content['description']);
            
            if (isset($content['piloteId'])) {
                $pilote = $piloteRepository->find($content['piloteId']);
                if (!$pilote) {
                    return $this->json(['message' => 'Pilote introuvable'], JsonResponse::HTTP_NOT_FOUND);
                }
                $infraction->setPilote($pilote);

                if ($content['penalite'] ?? 0) {
                    $Points = $pilote->getPointsLicence() - $content['penalite'];
                    $pilote->setPointsLicence($Points);
                }
            }

            if (isset($content['ecurieId'])) {
                $ecurie = $ecurieRepository->find($content['ecurieId']);
                if (!$ecurie) {
                    return $this->json(['message' => 'Ecurie introuvable'], JsonResponse::HTTP_NOT_FOUND);
                }
                $infraction->setEcurie($ecurie);
            }

            
            $entityManager->persist($infraction);
            $entityManager->flush();

            return new JsonResponse("Insertionde la nouvelle infraction effectuée à l'id : ".$infraction->getId());
       // } else {
        //    return new JsonResponse("Vous n'avez pas les accès nécessaires", JsonResponse::HTTP_FORBIDDEN);
       // }
    }
}
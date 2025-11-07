<?php

// src/Controller/CategoryController.php
namespace App\Controller;

// ...
use App\Entity\Pilote;
use App\Entity\Ecurie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[Route('/f1')]// a modifier
class PiloteController extends AbstractController
{
    #[Route('/createPilote', name: 'create_pilote', methods: ['POST'])]
    public function createPilote(
        Request $request,
        EntityManagerInterface $entityManager,

    ): JsonResponse
    {
        //$hasAccess = $this->isGranted('ROLE_ADMIN');

        //if ($hasAccess) {
            $content = json_decode($request->getContent(), true);
            $nomEcurie = $content['ecurie'];

            if (!$nomEcurie) {
                return new JsonResponse(['error' => " nom de l'écurie manquant"], 400);
            }

            $ecurie = $entityManager->getRepository(Ecurie::class)->findOneBy(['nom' => $nomEcurie]);

            if (!$ecurie) {
                return new JsonResponse([
                    'error' => sprintf('Écurie introuvable', $nomEcurie)
                ], 404);
            }

            $pilote = new Pilote();
            $pilote->setEcurie($ecurie);
            $pilote->setNom($content['nom']);
            $pilote->setPrenom($content['prenom']);
            $pilote->setPointsLicence($content['pointsLicence']);
            $pilote->setPoste($content['poste']);
            
            
            $entityManager->persist($pilote);
            $entityManager->flush();

            return new JsonResponse("Insertion du pilote effectuée à l'id : ".$pilote->getId());
       // } else {
        //    return new JsonResponse("Vous n'avez pas les accès nécessaires", JsonResponse::HTTP_FORBIDDEN);
       // }
    }
}
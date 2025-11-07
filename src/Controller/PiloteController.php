<?php

// src/Controller/CategoryController.php
namespace App\Controller;

// ...
use App\Entity\Ecurie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[Route('/api/category')]// a modifier
class PiloteController extends AbstractController
{
    #[Route('/createEcurie', name: 'create_ecurie', methods: ['POST'])]
    public function createEcurie(
        Request $request,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');

        if ($hasAccess) {
            $content = json_decode($request->getContent(), true);

            $ecurie = new Ecurie();
            if((!isset($content['nom'])) || (!isset($content['marque']))) {
                return new JsonResponse('champ name ou marque manquant', JsonResponse::HTTP_BAD_REQUEST);
            }
            $ecurie->setNom($content['nom']);
            $ecurie->setMarque($content['marque']);

            // tell Doctrine you want to (eventually) save the Product (no queries yet)
            $entityManager->persist($ecurie);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return new JsonResponse('Saved new category with id '.$ecurie->getId());
        } else {
            return new JsonResponse("Vous n'avez pas les accès nécessaires", JsonResponse::HTTP_FORBIDDEN);
        }
    }
}
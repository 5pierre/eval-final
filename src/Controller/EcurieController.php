<?php


namespace App\Controller;

// ...
use App\Entity\Ecurie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[Route('/f1')]// 
class EcurieController extends AbstractController
{
    #[Route('/createEcurie', name: 'create_ecurie', methods: ['POST'])]
    public function createEcurie(
        Request $request,
        EntityManagerInterface $entityManager,

    ): JsonResponse
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');

        if ($hasAccess) {
            $content = json_decode($request->getContent(), true);

            $ecurie = new Ecurie();
            $ecurie->setNom($content['nom']);
            $ecurie->setMarque($content['marque']);
            
            
            $entityManager->persist($ecurie);
            $entityManager->flush();

            return new JsonResponse("Insertionde la nouvelle écurie effectuée à l'id : ".$ecurie->getId());
       } else {
           return new JsonResponse("Vous n'avez pas les accès nécessaires", JsonResponse::HTTP_FORBIDDEN);
       }
    }
}
<?php


namespace App\Controller;

// ...
use App\Entity\Ecurie;
use App\Entity\Pilote;
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
        //$hasAccess = $this->isGranted('ROLE_ADMIN');

        //if ($hasAccess) {
            $content = json_decode($request->getContent(), true);

            $ecurie = new Ecurie();
            $ecurie->setNom($content['nom']);
            $ecurie->setMarque($content['marque']);
            
            
            $entityManager->persist($ecurie);
            $entityManager->flush();

            return new JsonResponse("Insertionde la nouvelle écurie effectuée à l'id : ".$ecurie->getId());
       // } else {
        //    return new JsonResponse("Vous n'avez pas les accès nécessaires", JsonResponse::HTTP_FORBIDDEN);
       // }
    }

   #[Route('/{id}/editPilotes', name: 'edit_ecurie_pilotes', methods: ['PATCH'])]
    public function editPilotes(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {
        $content = json_decode($request->getContent(), true);

        if (!isset($content['pilotes']) || !is_array($content['pilotes'])) {
            return new JsonResponse(['message' => 'Champ "pilotes" manquant ou invalide'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $ecurie = $entityManager->getRepository(Ecurie::class)->find($id);
        if (!$ecurie) {
            return new JsonResponse(['message' => 'Écurie introuvable'], JsonResponse::HTTP_NOT_FOUND);
        }

        $piloteRepo = $entityManager->getRepository(Pilote::class);
        $nouveauxPilotes = [];
        foreach ($content['pilotes'] as $piloteId) {
            $pilote = $piloteRepo->find($piloteId);
            if ($pilote) {
                $nouveauxPilotes[] = $pilote;
            }
        }

        $autreEcurie = null;
        if (isset($content['autreEcurieId'])) {
            $autreEcurie = $entityManager->getRepository(Ecurie::class)->find($content['autreEcurieId']);
            if (!$autreEcurie) {
                return new JsonResponse(['message' => 'Écurie de remplacement introuvable'], JsonResponse::HTTP_BAD_REQUEST);
            }
        }

        foreach ($ecurie->getPilotes() as $pilote) {
            if (!in_array($pilote, $nouveauxPilotes, true)) {
                if ($autreEcurie) {
                    $pilote->setEcurie($autreEcurie);
                    $autreEcurie->addPilote($pilote);
                } else {
                    continue;
                }
                $ecurie->removePilote($pilote);
            }
        }

        foreach ($nouveauxPilotes as $pilote) {
            $ecurie->addPilote($pilote);
        }

        $entityManager->flush();

        return new JsonResponse([
            'message' => 'Pilotes de l’écurie mis à jour',
            'ecurie_id' => $ecurie->getId(),
            'pilotes' => array_map(fn($p) => $p->getId(), $ecurie->getPilotes()->toArray())
        ]);
    }

}

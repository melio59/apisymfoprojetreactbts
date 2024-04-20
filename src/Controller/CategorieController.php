<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CategorieController extends AbstractController
{
    #[Route('/categorie', name: 'app_categorie')]
    public function index(CategorieRepository $categorieRepository, SerializerInterface $serializerInterface): JsonResponse
    {
        $categorieList = $categorieRepository->findAll();
        $jsonCategorieList = $serializerInterface->serialize($categorieList, 'json');
        return new JsonResponse(
            $jsonCategorieList, Response::HTTP_OK, [], true
        );
    }

    #[Route('/categorie/{id}', name: 'app_categorie_delete', methods: ['DELETE'])]
    public function delete(Categorie $categorie, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($categorie);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/categorie', name: 'app_categorie_add', methods: ['POST'])]
    public function add(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializerInterface): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Vérifiez si les données nécessaires sont présentes
        if (empty($data['nom_categorie'])) {
            return new JsonResponse(['message' => 'Missing required fields'], Response::HTTP_BAD_REQUEST);
        }

        $categorie = new Categorie();
        $categorie->setNomCategorie($data['nom_categorie']);

        $entityManager->persist($categorie);
        $entityManager->flush();

        $jsonCategorie = $serializerInterface->serialize($categorie, 'json');
        return new JsonResponse($jsonCategorie, Response::HTTP_CREATED, [], true);
    }

    #[Route('/categorie/{id}', name: 'app_categorie_update', methods: ['PUT'])]
    public function update(Request $request, Categorie $categorie, EntityManagerInterface $entityManager, SerializerInterface $serializerInterface): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Vérifiez si les données nécessaires sont présentes
        if (empty($data['nom_categorie'])) {
            return new JsonResponse(['message' => 'Missing required fields'], Response::HTTP_BAD_REQUEST);
        }

        $categorie->setNomCategorie($data['nom_categorie']);

        $entityManager->flush();

        $jsonCategorie = $serializerInterface->serialize($categorie, 'json');
        return new JsonResponse($jsonCategorie, Response::HTTP_OK, [], true);
    }
}

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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

class CategorieController extends AbstractController
{
    #[Route('/categorie', name: 'app_categorie', methods: ['GET'])]
    public function index(CategorieRepository $categorieRepository, SerializerInterface $serializerInterface): JsonResponse
    {
        $categorieList = $categorieRepository->findAll();
        $jsonCategorieList = $serializerInterface->serialize($categorieList, 'json');
        return new JsonResponse(
            $jsonCategorieList, Response::HTTP_OK, [], true
        );
    }

    #[Route('/categorie/{id}', name: 'app_categorie_show', methods: ['GET'])]
    public function show(int $id, CategorieRepository $categorieRepository, SerializerInterface $serializerInterface): JsonResponse
    {
        $categorie = $categorieRepository->find($id);
        $jsonCategorie = $serializerInterface->serialize($categorie, 'json');
        return new JsonResponse($jsonCategorie, Response::HTTP_OK, [], true);
    }

    #[Route('/categorie/{id}', name: 'app_categorie_delete', methods: ['DELETE'])]
    public function delete(Categorie $categorie, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($categorie);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/categorie', name: 'app_categorie_add', methods: ['POST'])]
    public function add(Request $request, EntityManagerInterface $entityManager, CategorieRepository $categorieRepository , SerializerInterface $serializerInterface, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $data = $serializerInterface->deserialize($request->getContent(), Categorie::class, 'json');
        if (empty($data->getNomCategorie())) {
            return new JsonResponse(['message' => 'Missing required fields'], Response::HTTP_BAD_REQUEST);
        }
        $entityManager->persist($data);
        $entityManager->flush();
        $jsonCategorie = $serializerInterface->serialize($data, 'json');
        $location = $urlGenerator->generate('app_categorie', ['id' => $data->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        return new JsonResponse($jsonCategorie, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route('/categorie/{id}', name: 'app_categorie_update', methods: ['PUT'])]
    public function update(Request $request, Categorie $categorie, EntityManagerInterface $entityManager, SerializerInterface $serializerInterface): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        
        if (empty($data['nom_categorie'])) {
            return new JsonResponse(['message' => 'Missing required fields'], Response::HTTP_BAD_REQUEST);
        }

        $categorie->setNomCategorie($data['nom_categorie']);

        $entityManager->flush();

        $jsonCategorie = $serializerInterface->serialize($categorie, 'json');
        return new JsonResponse($jsonCategorie, Response::HTTP_OK, [], true);
    }
}

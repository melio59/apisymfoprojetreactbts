<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository, SerializerInterface $serializerInterface): JsonResponse
    {
        $articleList = $articleRepository->findAll();
        $jsonArticleList = $serializerInterface->serialize($articleList, 'json');
        return new JsonResponse(
             $jsonArticleList, Response::HTTP_OK, [], true
        );
    }

    #[Route('/article/{id}', name: 'app_article_show', methods: ['GET'])]
    public function show(int $id, ArticleRepository $articleRepository, SerializerInterface $serializerInterface): JsonResponse
    {
        $article = $articleRepository->find($id);
        $jsonArticle = $serializerInterface->serialize($article, 'json');
        return new JsonResponse($jsonArticle, Response::HTTP_OK, [], true);
    }



    #[Route('/article/{id}', name: 'app_article_delete', methods: ['DELETE'])]
    public function delete(Article $article, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($article);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/article/{id}', name: 'app_article_update', methods: ['PUT'])]
    public function update(Request $request, Article $article, EntityManagerInterface $entityManager, SerializerInterface $serializerInterface): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        
        if (empty($data['nom']) || empty($data['description']) || empty($data['images']) || empty($data['date_peremp']) || empty($data['prix']) || empty($data['id_categorie']) || empty($data['stock'])) {
            return new JsonResponse(['message' => 'Missing required fields'], Response::HTTP_BAD_REQUEST);
        }

        
        $datePeremp = \DateTime::createFromFormat('Y-m-d', $data['date_peremp']);

        
        $article->setNom($data['nom']);
        $article->setDescription($data['description']);
        $article->setImages($data['images']);
        $article->setDatePeremp($datePeremp);
        $article->setPrix($data['prix']);
        $article->setIdCategorie($data['id_categorie']);
        $article->setStock($data['stock']);

        
        $entityManager->persist($article);
        $entityManager->flush();

        
        $jsonArticle = $serializerInterface->serialize($article, 'json');
        return new JsonResponse($jsonArticle, Response::HTTP_OK, [], true);
    }

    #[Route('/article', name: 'app_article_add', methods: ['POST'])]
    public function add(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializerInterface, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        
        $article = $serializerInterface->deserialize($request->getContent(), Article::class, 'json');

        
        if (empty($article->getNom()) || empty($article->getDescription()) || empty($article->getImages()) || empty($article->getDatePeremp()) || empty($article->getPrix()) || empty($article->getIdCategorie()) || empty($article->getStock())) {
            return new JsonResponse(['message' => 'Missing required fields'], Response::HTTP_BAD_REQUEST);
        }

       
        $entityManager->persist($article);
        $entityManager->flush();

        
        $location = $urlGenerator->generate('app_article_show', ['id' => $article->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

       
        $jsonArticle = $serializerInterface->serialize($article, 'json');

        
        return new JsonResponse($jsonArticle, Response::HTTP_CREATED, ["Location" => $location], true);
    }
}

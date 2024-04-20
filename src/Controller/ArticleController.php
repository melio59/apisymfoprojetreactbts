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
use Symfony\Component\Serializer\SerializerInterface;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function index(ArticleRepository $articleRepository, SerializerInterface $serializerInterface): JsonResponse
    {
        $articleList = $articleRepository->findAll();
        $jsonArticleList = $serializerInterface->serialize($articleList, 'json');
        return new JsonResponse(
             $jsonArticleList, Response::HTTP_OK, [], true
        );
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

        // Vérifiez si les données nécessaires sont présentes
        if (empty($data['nom']) || empty($data['description']) || empty($data['images']) || empty($data['date_peremp']) || empty($data['prix']) || empty($data['id_categorie']) || empty($data['stock'])) {
            return new JsonResponse(['message' => 'Missing required fields'], Response::HTTP_BAD_REQUEST);
        }

        // Créez un objet DateTime à partir de la date de péremption
        $datePeremp = \DateTime::createFromFormat('Y-m-d', $data['date_peremp']);

        // Mettez à jour les propriétés de l'article
        $article->setNom($data['nom']);
        $article->setDescription($data['description']);
        $article->setImages($data['images']);
        $article->setDatePeremp($datePeremp);
        $article->setPrix($data['prix']);
        $article->setIdCategorie($data['id_categorie']);
        $article->setStock($data['stock']);

        // Persistez les modifications
        $entityManager->persist($article);
        $entityManager->flush();

        // Sérialisez et renvoyez l'article mis à jour
        $jsonArticle = $serializerInterface->serialize($article, 'json');
        return new JsonResponse($jsonArticle, Response::HTTP_OK, [], true);
    }

    #[Route('/article', name: 'app_article_add', methods: ['POST'])]
    public function add(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializerInterface): JsonResponse
    {
        // Récupérer les données de la requête
        $data = json_decode($request->getContent(), true);

        // Vérifier si toutes les données requises sont présentes
        $requiredFields = ['nom', 'description', 'images', 'date_peremp', 'prix', 'id_categorie', 'stock'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                return new JsonResponse(['message' => 'Missing required fields'], Response::HTTP_BAD_REQUEST);
            }
        }

        // Créer un nouvel objet Article
        $article = new Article();
        $article->setNom($data['nom']);
        $article->setDescription($data['description']);
        $article->setImages($data['images']);
        $article->setDatePeremp(new \DateTime($data['date_peremp']));
        $article->setPrix($data['prix']);
        $article->setIdCategorie($data['id_categorie']);
        $article->setStock($data['stock']);

        // Persister le nouvel article dans la base de données
        $entityManager->persist($article);
        $entityManager->flush();

        // Sérialiser l'article créé et le renvoyer en réponse
        $jsonArticle = $serializerInterface->serialize($article, 'json');
        return new JsonResponse($jsonArticle, Response::HTTP_CREATED, [], true);
    }
}

<?php
namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Fournisseur;
use App\Repository\FournisseurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

class FournisseurController extends AbstractController
{
    #[Route('/fournisseur', name: 'app_fournisseur', methods: ['GET'])]
    public function index(FournisseurRepository $FournisseurRepository, SerializerInterface $serializerInterface): JsonResponse
    {
        $FournisseurList = $FournisseurRepository->findAll();
        $jsonFournisseurList = $serializerInterface->serialize($FournisseurList, 'json');
        return new JsonResponse(
             $jsonFournisseurList, Response::HTTP_OK, [], true
        );
    }


    #[Route('/fournisseur/{id}', name: 'app_fournisseur_show', methods: ['GET'])]
    public function show(int $id, FournisseurRepository $fournisseurRepository, SerializerInterface $serializerInterface): JsonResponse
    {
        $fournisseur = $fournisseurRepository->find($id);

        // Vérifier si le fournisseur existe
        if (!$fournisseur) {
            return new JsonResponse(['message' => 'Fournisseur not found'], Response::HTTP_NOT_FOUND);
        }

        // Sérialiser l'entité Fournisseur en JSON pour la réponse
        $jsonFournisseur = $serializerInterface->serialize($fournisseur, 'json');

        return new JsonResponse($jsonFournisseur, Response::HTTP_OK, [], true);
    }

    #[Route('/fournisseur/{id}', name: 'app_fournisseur_delete', methods: ['DELETE'])]
    public function delete(Fournisseur $fournisseur, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($fournisseur);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/fournisseur', name: 'app_fournisseur_add', methods: ['POST'])]
    public function add(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializerInterface, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        // Désérialisation des données JSON en un objet Fournisseur
        $fournisseur = $serializerInterface->deserialize($request->getContent(), Fournisseur::class, 'json');

        // Vérification si le champ requis est présent
        if (empty($fournisseur->getNomFournisseur()) || empty($fournisseur->getNumeroTelephone()) || empty($fournisseur->getAdresse())) {
            return new JsonResponse(['message' => 'Missing required fields'], Response::HTTP_BAD_REQUEST);
        }

        // Persister l'entité Fournisseur
        $entityManager->persist($fournisseur);
        $entityManager->flush();

        // Générer l'URL de la nouvelle ressource
        $location = $urlGenerator->generate('app_fournisseur_show', ['id' => $fournisseur->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        // Sérialiser l'entité pour la réponse
        $jsonFournisseur = $serializerInterface->serialize($fournisseur, 'json');

        // Retourner une réponse avec les données de la nouvelle ressource et l'URL de localisation
        return new JsonResponse($jsonFournisseur, Response::HTTP_CREATED, ["Location" => $location], true);
    }
    #[Route('/fournisseur/{id}', name: 'app_fournisseur_update', methods: ['PUT'])]
    public function update(Request $request, Fournisseur $fournisseur, EntityManagerInterface $entityManager, SerializerInterface $serializerInterface): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        
        if (empty($data['nomFournisseur']) || empty($data['numeroTelephone']) || empty($data['adresse'])) {
            return new JsonResponse(['message' => 'Missing required fields'], Response::HTTP_BAD_REQUEST);
        }

        $fournisseur->setNomFournisseur($data['nomFournisseur']);
        $fournisseur->setNumeroTelephone($data['numeroTelephone']);
        $fournisseur->setAdresse($data['adresse']);

        $entityManager->flush();

        $jsonFournisseur = $serializerInterface->serialize($fournisseur, 'json');
        return new JsonResponse($jsonFournisseur, Response::HTTP_OK, [], true);
    }
}

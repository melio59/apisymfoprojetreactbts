<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;

class CommandeController extends AbstractController
{
    #[Route('/commandes', name: 'app_commandes_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository, SerializerInterface $serializerInterface): JsonResponse
    {
        $commandes = $commandeRepository->findAll();
        $jsonCommandes = $serializerInterface->serialize($commandes, 'json');

        return new JsonResponse($jsonCommandes, Response::HTTP_OK, [], true);
    }

    #[Route('/commandes/{id}', name: 'app_commandes_show', methods: ['GET'])]
    public function show(int $id, CommandeRepository $commandeRepository, SerializerInterface $serializerInterface): JsonResponse
    {
        $commande = $commandeRepository->find($id);

        if (!$commande) {
            return new JsonResponse(['message' => 'Commande not found'], Response::HTTP_NOT_FOUND);
        }

        $jsonCommande = $serializerInterface->serialize($commande, 'json');

        return new JsonResponse($jsonCommande, Response::HTTP_OK, [], true);
    }

    #[Route('/commandes', name: 'app_commandes_add', methods: ['POST'])]
    public function add(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializerInterface): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $commande = new Commande();
        $commande->setDateCommande(new \DateTime($data['dateCommande']));
        $commande->setStatus($data['status']);

        $entityManager->persist($commande);
        $entityManager->flush();

        $jsonCommande = $serializerInterface->serialize($commande, 'json');

        return new JsonResponse($jsonCommande, Response::HTTP_CREATED, [], true);
    }

    #[Route('/commandes/{id}', name: 'app_commandes_update', methods: ['PUT'])]
    public function update(Request $request, Commande $commande, EntityManagerInterface $entityManager, SerializerInterface $serializerInterface): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $commande->setDateCommande(new \DateTime($data['dateCommande']));
        $commande->setStatus($data['status']);

        $entityManager->flush();

        $jsonCommande = $serializerInterface->serialize($commande, 'json');
        return new JsonResponse($jsonCommande, Response::HTTP_OK, [], true);
    }

    #[Route('/commandes/{id}', name: 'app_commandes_delete', methods: ['DELETE'])]
    public function delete(Commande $commande, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($commande);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}

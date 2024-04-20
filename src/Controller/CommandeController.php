<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CommandeController extends AbstractController
{
    #[Route('/commande', name: 'app_commande')]

    public function index(CommandeRepository $CommandeRepository, SerializerInterface $serializerInterface): JsonResponse
    {
        $CommandeList = $CommandeRepository->findAll();
        $jsonCommandeList = $serializerInterface->serialize($CommandeList, 'json');
        return new JsonResponse(
             $jsonCommandeList, Response::HTTP_OK, [], true


        );
    }
}

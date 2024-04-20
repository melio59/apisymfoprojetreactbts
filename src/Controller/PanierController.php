<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Repository\PanierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(PanierRepository $panierRepository, SerializerInterface $serializerInterface): JsonResponse
    {
        $PanierList = $PanierRepository->findAll();
        $jsonPanierList = $serializerInterface->serialize($PanierList, 'json');
        return new JsonResponse(
             $jsonPanierList, Response::HTTP_OK, [], true


        );
    }
}

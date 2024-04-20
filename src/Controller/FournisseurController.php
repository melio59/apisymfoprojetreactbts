<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Repository\FournisseurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class FournisseurController extends AbstractController
{
    #[Route('/fournisseur', name: 'app_fournisseur')]
    public function index(FournisseurRepository $FournisseurRepository, SerializerInterface $serializerInterface): JsonResponse
    {
        $FournisseurList = $FournisseurRepository->findAll();
        $jsonFournisseurList = $serializerInterface->serialize($FournisseurList, 'json');
        return new JsonResponse(
             $jsonFournisseurList, Response::HTTP_OK, [], true


        );
    }
}

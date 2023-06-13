<?php

namespace App\Controller;

use App\Repository\ActualiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ApiActualiteController extends AbstractController
{
    /**@Route("/api/actualites", name="api_actualites_index", methods={"GET"})*/
    
  public function index(ActualiteRepository $actualiteRepository): Response{

     return $this->json($actualiteRepository->findAll(), 200,[], ['groups' => 'actualite:read']);

    }
}
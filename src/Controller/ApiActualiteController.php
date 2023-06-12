<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiActualiteController extends AbstractController
{
    /**
     * @Route("/api/actualite", name="app_api_actualite")
     */
    public function index(): Response
    {
        return $this->render('api_actualite/index.html.twig', [
            'controller_name' => 'ApiActualiteController',
        ]);
    }
}

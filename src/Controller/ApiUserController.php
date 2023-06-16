<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;



class ApiUserController extends AbstractController
{
    /**@Route("/api/user", name="api_user_index", methods={"GET"})*/
    
  public function index(UserRepository $UserRepository): Response{

     return $this->json($UserRepository->findAll(), 200,[], ['groups' => 'user:read']);

    }

 /**
 * @Route("/api/user/login", name="api_user_login", methods={"POST"})
 */
public function login(Request $request, UserRepository $userRepository): JsonResponse
{
    $email = $request->request->get('email');
    $password = $request->request->get('password');
    
    $user = $userRepository->findOneBy(['email' => $email]);
    
    if (!$user || !password_verify($password, $user->getMotDePasse())) {
        return new JsonResponse(['message' => 'Erreur de connexion. Veuillez vérifier vos informations.'], 401);
    }
    
    // Connexion réussie
    return new JsonResponse(['message' => 'Connexion réussie'], 200);
}
}
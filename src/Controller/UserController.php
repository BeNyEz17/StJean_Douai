<?php

// src/Controller/UserController.php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/ajout_user", name="app_ajout_user")
     */
    public function createUser(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
       if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

    $this->addFlash('success', 'Utilisateur créé avec succès.');

    return $this->redirectToRoute('ajout_user');
}


        return $this->render('user/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

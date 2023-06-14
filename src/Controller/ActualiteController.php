<?php

namespace App\Controller;

use App\Entity\Actualite;
use App\Form\ActualiteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActualiteController extends AbstractController
{
    /**
     * @Route("", name="app_actualite_index", methods={"GET", "POST"})
     */
    public function index(Request $request): Response
    {
        $actualite = new Actualite();
        $form = $this->createForm(ActualiteType::class, $actualite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer le téléchargement du fichier
            /** @var UploadedFile $file */
            $file = $form->get('image')->getData();

            // Générez un nom unique pour le fichier
            $fileName = $file->getClientOriginalName();

            // Déplacez le fichier dans le répertoire de destination
            try {
                $file->move(
                    $this->getParameter('upload_directory'), // Remplacez par le répertoire de destination souhaité
                    $fileName
                );
            } catch (FileException $e) {
                // Gérer les exceptions liées au téléchargement du fichier
                // Par exemple, tu peux ajouter un message flash d'erreur
                $this->addFlash('error', 'Une erreur s\'est produite lors du téléchargement du fichier.');
                return $this->redirect($request->getUri());
            }

            // Mettez à jour l'entité avec le nom du fichier
            $actualite->setImage($fileName);

            // Enregistrez l'entité en base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($actualite);
            $entityManager->flush();

            $this->addFlash('success', 'Actualité créée avec succès.');

            return $this->redirect($request->getUri());
        }

        return $this->render('index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // ...
}
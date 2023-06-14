<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\CSVImportUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CSVImportUserController extends AbstractController
{
    /**
     * @Route("/import_user_csv", name="import_user_csv")
     */
    public function import(Request $request)
    {
        $form = $this->createForm(CSVImportUserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $csvFile = $form->get('csv_file')->getData();

            // Vérifier si un fichier CSV a été soumis
            if ($csvFile) {
                // Vérifier si le fichier est un fichier CSV valide
                $fileExtension = $csvFile->getClientOriginalExtension();
                if ($fileExtension === 'csv') {
                    $csvData = file_get_contents($csvFile->getPathname());
                    $rows = explode("\n", $csvData);

                    // Parcourir chaque ligne du fichier CSV
                    foreach ($rows as $row) {
                        $userData = str_getcsv($row, ';');

                        // Vérifier si la ligne contient les données utilisateur requises (nom, prénom, email, mot de passe)
                       // ...

                        
                        if (count($userData) >= 6) {
                            $nom = $userData[0];
                            $prenom = $userData[1];
                            $email = $userData[2];
                            $motDePasse = $userData[3];
                            $adresse = $userData[4];
                            $dateDeNaissance = \DateTime::createFromFormat('Y-m-d', $userData[5]);
                        
                            // Créer un nouvel utilisateur et l'enregistrer dans la base de données
                            $user = new User();
                            $user->setNom($nom);
                            $user->setPrenom($prenom);
                            $user->setEmail($email);
                            $user->setMotDePasse($motDePasse); // Affecter le mot de passe sans le hacher
                            $user->setAdresse($adresse);
                            $user->setDateDeNaissance($dateDeNaissance);
                        
                            $entityManager = $this->getDoctrine()->getManager();
                            $entityManager->persist($user);
                            $entityManager->flush();
                        }
                    }

                    // Flash message pour indiquer que l'importation a réussi
                    $this->addFlash('success', 'Importation CSV réussie.');
                } else {
                    // Flash message pour indiquer que le fichier n'est pas un fichier CSV valide
                    $this->addFlash('error', 'Le fichier doit être un fichier CSV.');
                }
            } else {
                // Flash message pour indiquer qu'aucun fichier n'a été soumis
                $this->addFlash('error', 'Veuillez sélectionner un fichier CSV.');
            }
        }

        return $this->render('csv_import_user/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // ...
}

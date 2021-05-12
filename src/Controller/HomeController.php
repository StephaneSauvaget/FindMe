<?php

/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

use App\Model\ApiManager;

class HomeController extends AbstractController
{
    /**
     * Display home page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //TODO Add your code here to create a new accessory

            $uploadDir = 'public/uploads/';
            /* le nom de fichier sur le serveur est ici généré à partir du nom
            de fichier sur le poste du client (mais d'autre stratégies de nommage sont possibles)*/
            $uploadFile = $uploadDir . basename($_FILES['image']['name']);
            // Je récupère l'extension du fichier
            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            // Les extensions autorisées
            $extensions_ok = ['jpg','jpeg','png'];
            // Le poids max géré par PHP par défaut est de 2M
            $maxFileSize = 1500000;

            // Je sécurise et effectue mes tests

            /****** Si l'extension est autorisée *************/
            if (!in_array($extension, $extensions_ok)) {
                $errors[] = 'Veuillez sélectionner une image de type Jpg ou Jpeg ou Png !';
            }

            /****** On vérifie si l'image existe et si le poids est autorisé en octets *************/
            if (file_exists($_FILES['image']['tmp_name']) && filesize($_FILES['image']['tmp_name']) > $maxFileSize) {
                $errors[] = "Votre fichier doit faire moins de 1,5MB !";
            }
        }
        if ($errors) {
            foreach ($errors as $error) {
                echo '<p>' . $error . '</p>' . PHP_EOL;
            }
        }
        $apiManager = new ApiManager();
        $pictures = $apiManager->addApi();
        return $this->twig->render('Home/index.html.twig', ['pictures' => $pictures]);
    }
}

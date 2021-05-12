<?php

namespace App\Controller;

use App\Model\CompareManager;

class CompareController extends AbstractController
{
    public function play(string $picture1, string $picture2)
    {

        $picture1 = "assets/images/test1.png";
        $picture2 = "assets/images/test2.png";
        $picture3 = "assets/images/favicon.png";

        $compareManager = new CompareManager();
        $dissimilarity = $compareManager->compare($picture1, $picture3);
        return $this->twig->render('Compare/compare.html.twig', ['dissimilarity' => $dissimilarity]);
    }
}

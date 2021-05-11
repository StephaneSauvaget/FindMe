<?php

namespace App\Controller;

use App\Model\CompareManager;

class CompareController extends AbstractController
{
    public function play(string $picture1, string $picture2)
    {
        var_dump(is_file("assets/images/" . $picture1 . ".png"));
        var_dump($picture2);

        $compareManager = new CompareManager();
        $dissimilarity = $compareManager->compare($picture1, $picture2);
        return $this->twig->render('Compare/compare.html.twig', ['dissimilarity' => $dissimilarity]);
    }
}

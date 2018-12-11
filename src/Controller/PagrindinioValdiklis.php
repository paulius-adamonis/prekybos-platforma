<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PagrindinioValdiklis extends AbstractController
{
    /**
     * @Route("/", name="app_main")
     */
    public function index()
    {
        $message = "Gero naudojimo!";

        return $this->render('main/index.html.twig', [
            'title' => 'Pagrindinis',
            'message' => $message
        ]);
    }
}

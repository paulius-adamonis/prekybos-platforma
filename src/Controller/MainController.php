<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_main")
     */
    public function index()
    {
        return $this->render('main/index.html.twig', [
            'title' => 'Pagrindinis',
            'message' => "Sveiki atvykę į pagrindinį puslapį."
        ]);
    }
}

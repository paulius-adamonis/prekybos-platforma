<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IslaiduController extends AbstractController
{
    /**
     * @Route("/islaidu", name="islaidu")
     */
    public function index()
    {
        return $this->render('islaidu/index.html.twig', [
            'controller_name' => 'IslaiduController',
        ]);
    }
}

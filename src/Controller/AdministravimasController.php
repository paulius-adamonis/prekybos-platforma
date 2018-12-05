<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class AdministravimasController
 * @package App\Controller
 * @IsGranted("ROLE_ADMIN")
 */
class AdministravimasController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_main")
     */
    public function index()
    {
        return $this->render('administravimas/index.html.twig', [
            'title' => 'AdministravimasController',
        ]);
    }
}

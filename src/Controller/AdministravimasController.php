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
            'title' => 'Administravimo posistemė',
            'message' => 'Sveiki prisijungę prie administravimo panelės! Norėdami ką nors atlikti, spauskite atitinkamą mygtuką šoninėje navigacijos juostoje.'
        ]);
    }

    /**
     * @Route("/admin/parduotuve", name="admin_parduotuve")
     */
    public function parduotuvesValdymas()
    {
        return $this->render('administravimas/parduotuve.html.twig', [
            'title' => 'E-parduotuvės valdymas'
        ]);
    }

    /**
     * @Route("/admin/turgus", name="admin_turgus")
     */
    public function turgausValdymas()
    {
        return $this->render('administravimas/turgus.html.twig', [
            'title' => 'E-turgaus valdymas'
        ]);
    }

    /**
     * @Route("/admin/vartotojai", name="admin_vartotojai")
     */
    public function vartotojuValdymas()
    {
        return $this->render('administravimas/vartotojai.html.twig', [
            'title' => 'Vartotojų valdymas'
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Sandelis;
use App\Entity\SandeliuPriklausymas;
use App\Form\SandelisType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class SandelioController extends AbstractController
{
    /**
     * @Route("/sandelis", name="sandelis")
     */
    public function index()
    {
        $auth_checker = $this->get('security.authorization_checker');
        if($auth_checker->isGranted('ROLE_ADMIN'))
        {
            $sandeliai = $this->getDoctrine()->getRepository(Sandelis::class)->findAll();
            return $this->render('sandelio/index.html.twig',
                array ('sandeliai' => $sandeliai,
                    'title' => 'Sandeliai'));
        }
        elseif ($auth_checker->isGranted('ROLE_SANDELIO_DARBUOTOJAS') ||
            $auth_checker->isGranted('ROLE_SANDELIO_VALDYTOJAS'))
        {
            $user = $this->getUser();
            $userId = $user->getId();
            $sandeliopriklausomumas = $this->getDoctrine()->getRepository(SandeliuPriklausymas::class)->findOneBy(array(
                'fkVartotojas' => $userId
            ));
            $sandelis = $sandeliopriklausomumas->getfkSandelis();
            $sandelioID = $sandelis->getId();
            return $this->redirect('sandelis/'.$sandelioID);
        }
        else{
            return $this->redirectToRoute('app_main');
        }
    }


}

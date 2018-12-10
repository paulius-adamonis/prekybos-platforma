<?php

namespace App\Controller;

use App\Entity\Islaidos;
use App\Entity\IslaidosTipas;
use App\Entity\PrekiuPriklausymas;
use App\Entity\Sandelis;
use App\Entity\SandeliuPriklausymas;
use App\Form\SandelioIslaidaType;
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

    /**
     * @Route("/sandelis/{sandelioId}/islaida", name="islaida")
     */
    public function indexIslaidos($sandelioId)
    {
        $auth_checker = $this->get('security.authorization_checker');
        if($auth_checker->isGranted('ROLE_ADMIN') ||
            $auth_checker->isGranted('ROLE_SANDELIO_DARBUOTOJAS')) {
            $sandelis = $this->getDoctrine()->getRepository(Sandelis::class)->findOneBy(array(
                'id' => $sandelioId
            ));
            $islaidos = $this->getDoctrine()->getRepository(Islaidos::class)->findBy(array(
                'fkSandelis' => $sandelis
            ));
            if ($sandelis) {
                return $this->render('sandelio/islaidu.html.twig', array(
                    'sandelis' => $sandelis,
                    'title' => 'Išlaidos',
                    'islaidos' => $islaidos,
                    'sandelioId' => $sandelioId));
            } else
                return $this->redirect('/sandelis/' . $sandelioId);
        }
        else {
            return $this->redirectToRoute('app_main');
        }
    }

    /**
     * @Route("/sandelis/{sandelioId}/islaida/new", name="new_islaida")
     * Method({"GET", "POST"})
     */
    public function newIslaida(Request $request, $sandelioId)
    {
        $auth_checker = $this->get('security.authorization_checker');
        if($auth_checker->isGranted('ROLE_ADMIN') ||
            $auth_checker->isGranted('ROLE_SANDELIO_VALDYTOJAS')) {
            $islaida = new Islaidos();
            $tipai = $this->getDoctrine()->getRepository(IslaidosTipas::class)->findAll();
            $form = $this->createForm(SandelioIslaidaType::class, $islaida, array(
                'tipai' => $tipai
            ));
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $islaida = $form->getData();
                $sandelis = $this->getDoctrine()->getRepository(Sandelis::class)->findOneBy(
                    array('id' => $sandelioId));
                $islaida
                    ->setFkSandelis($sandelis)
                    ->setData(new \DateTime())
                ;
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($islaida);
                $entityManager->flush();
                return $this->redirect('/sandelis/' . $sandelioId.'/islaida');
            }
            return $this->render('sandelio/islaiduNew.html.twig', array(
                'form' => $form->createView(),
                'title' => 'Pridėti išlaidą į sandėlį'));
        }
        else {
            return $this->redirectToRoute('app_main');
        }
    }

    /**
     * @Route("/sandelis/{sandelioId}/trukstama", name="trukstamos")
     */
    public function indexTrukstamos($sandelioId)
    {
        $auth_checker = $this->get('security.authorization_checker');
        if($auth_checker->isGranted('ROLE_ADMIN') ||
            $auth_checker->isGranted('ROLE_SANDELIO_VALDYTOJAS')) {
            $sandelis = $this->getDoctrine()->getRepository(Sandelis::class)->findOneBy(array(
                'id' => $sandelioId
            ));
            $prekes = $this->getDoctrine()->getRepository(PrekiuPriklausymas::class)->findPrekesBySandelisNotDeleted($sandelioId);
            if ($sandelis) {
                return $this->render('sandelio/indexTrukstamos.html.twig', array(
                    'sandelis' => $sandelis,
                    'title' => 'Trukstamos prekės',
                    'prekes' => $prekes,
                    'sandelioId' => $sandelioId));
            } else return $this->redirectToRoute('sandelis');
        }
        else {
            return $this->redirectToRoute('app_main');
        }
    }



}

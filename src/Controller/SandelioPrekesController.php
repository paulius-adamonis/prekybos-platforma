<?php

namespace App\Controller;

use App\Entity\ParduotuvesPreke;
use App\Entity\PrekiuPriklausymas;
use App\Entity\Sandelis;
use App\Entity\SandeliuPriklausymas;
use App\Form\SandelioPrekesType;
use App\Form\SandelisType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class SandelioPrekesController extends AbstractController
{
    /**
     * @Route("/sandelis/{sandelioId}", name="prekes")
     */
    public function index($sandelioId)
    {
        $auth_checker = $this->get('security.authorization_checker');
        if($auth_checker->isGranted('ROLE_ADMIN') ||
            $auth_checker->isGranted('ROLE_SANDELIO_DARBUOTOJAS') ||
            $auth_checker->isGranted('ROLE_SANDELIO_VALDYTOJAS')) {
            $sandelis = $this->getDoctrine()->getRepository(Sandelis::class)->findOneBy(array(
                'id' => $sandelioId
            ));
            $prekes = $this->getDoctrine()->getRepository(ParduotuvesPreke::class)->findPrekesBySandelis($sandelioId);
            if ($sandelis) {
                return $this->render('sandelio/show.html.twig', array(
                    'sandelis' => $sandelis,
                    'title' => 'Sandėlis',
                    'prekes' => $prekes,
                    'sandelioId' => $sandelioId));
            } else return $this->redirectToRoute('sandelis');
        }
        else {
            return $this->redirectToRoute('app_main');
        }
    }

    /**
     * @Route("/sandelis/{sandelioId}/prekenew", name="new_preke")
     * Method({"GET", "POST"})
     */
    public function new(Request $request, $sandelioId)
    {
        $preke = new ParduotuvesPreke();
        $form = $this->createForm(SandelioPrekesType::class, $preke);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $preke = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($preke);
            $entityManager->flush();
            $prekesPriklausymas = new PrekiuPriklausymas();
            return $this->redirect('/sandelis/'.$sandelioId);
        }
        return $this->render('sandelio_prekes/new.html.twig', array(
            'form' => $form->createView(),
            'title' => 'Pridėti preke'));
    }

    /**
     * @Route("/sandelis/{sandelioId}/preke/edit/{id}", name="edit_preke")
     * Method({"GET", "POST"})
     */
    public function edit(Request $request,$sandelioId, $id){
        $auth_checker = $this->get('security.authorization_checker');
        if($auth_checker->isGranted('ROLE_ADMIN')) {
            $preke = $this->getDoctrine()->getRepository(ParduotuvesPreke::class)->findOneBy(array(
                'id' => $id
            ));
            if ($preke !== null) {
                $form = $this->createForm(SandelioPrekesType::class, $preke);
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->flush();
                    return $this->redirect('/sandelis/'.$sandelioId);
                }
                return $this->render('sandelio_prekes/new.html.twig', array(
                    'form' => $form->createView(),
                    'title' => 'Tvarkyti prekę'));
            }
            return $this->redirect('/sandelis/'.$sandelioId);
        }
        else {
            return $this->redirectToRoute('app_main');
        }
    }

    /**
     * @Route("/sandelis/{sandelioId}/preke/remove/{id}", name="remove_preke")
     * Method({"GET"})
     */
    public function remove($sandelioId,$id) {
        $auth_checker = $this->get('security.authorization_checker');
        if($auth_checker->isGranted('ROLE_ADMIN')) {
            $preke = $this->getDoctrine()->getRepository(ParduotuvesPreke::class)->findOneBy(array(
                'id' => $id
            ));
            if ($preke !== null) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($preke);
                $entityManager->flush();
                $this->get('session')->getFlashBag()->add(
                    'successful',
                    'Sandelis sėkmingai pašalintas'
                );
                return $this->redirect('/sandelis/'.$sandelioId);
            }
            return $this->redirect('/sandelis/'.$sandelioId);
        }
        else {
            return $this->redirectToRoute('app_main');
        }
    }

    /**
     * @Route("/sandelis/{sandelioId}/preke/{id}", name="preke_details")
     *
     */
    public function Show($sandelioId,$id)
    {
        $auth_checker = $this->get('security.authorization_checker');
        if($auth_checker->isGranted('ROLE_ADMIN') ||
            $auth_checker->isGranted('ROLE_SANDELIO_DARBUOTOJAS') ||
            $auth_checker->isGranted('ROLE_SANDELIO_VALDYTOJAS')) {
            $preke = $this->getDoctrine()->getRepository(ParduotuvesPreke::class)->findOneBy(array(
                'id' => $id
            ));
            $prekesPriklausymas = $this->getDoctrine()->getRepository(PrekiuPriklausymas::class)->findOneBy(array(
                'fkSandelis' => $sandelioId,
                'fkParduotuvesPreke' => $id
            ));
            if ($preke && $prekesPriklausymas) {
                return $this->render('sandelio_prekes/show.html.twig', array('preke' => $preke,
                    'title' => 'Prekė'));
            } else
                return $this->redirect('/sandelis/'.$sandelioId);
        }
        else {
            return $this->redirectToRoute('app_main');
        }
    }
}

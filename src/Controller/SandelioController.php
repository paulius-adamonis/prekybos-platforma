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

    /**
     * @Route("/sandelis/new", name="new_sandelis")
     * Method({"GET", "POST"})
     */
    public function new(Request $request){
        $auth_checker = $this->get('security.authorization_checker');
        if($auth_checker->isGranted('ROLE_ADMIN')) {

            $sandelis = new Sandelis();
            $form = $this->createForm(SandelisType::class, $sandelis);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $sandelis = $form->getData();
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($sandelis);
                $entityManager->flush();
                return $this->redirectToRoute('sandelis');
            }
            return $this->render('sandelio/new.html.twig', array(
                'form' => $form->createView(),
                'title' => 'Pridėti sandėlį'));
        }
        else{
            return $this->redirectToRoute('app_main');
        }
    }

    /**
     * @Route("/sandelis/edit/{id}", name="edit_sandelis")
     * Method({"GET", "POST"})
     */
    public function edit(Request $request, $id){
        $auth_checker = $this->get('security.authorization_checker');
        if($auth_checker->isGranted('ROLE_ADMIN')) {
            $sandelis = $this->getDoctrine()->getRepository(Sandelis::class)->findOneBy(array(
                'id' => $id
            ));
            if ($sandelis !== null) {
                $form = $this->createForm(SandelisType::class, $sandelis);
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->flush();
                    return $this->redirectToRoute('sandelis');
                }
                return $this->render('sandelio/new.html.twig', array(
                    'form' => $form->createView(),
                    'title' => 'Tvarkyti sandėlį'));
            }
            return $this->redirectToRoute('sandelis');
        }
        else {
            return $this->redirectToRoute('app_main');
        }
    }

    /**
     * @Route("/sandelis/remove/{id}", name="remove_sandelis")
     * Method({"GET"})
     */
    public function remove($id) {
        $auth_checker = $this->get('security.authorization_checker');
        if($auth_checker->isGranted('ROLE_ADMIN')) {
            $sandelis = $this->getDoctrine()->getRepository(Sandelis::class)->findOneBy(array(
                'id' => $id
            ));
            if ($sandelis !== null) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($sandelis);
                $entityManager->flush();
                $this->get('session')->getFlashBag()->add(
                    'successful',
                    'Sandelis sėkmingai pašalintas'
                );
                return $this->redirectToRoute('sandelis');
            }
            return $this->redirectToRoute('sandelis');
        }
        else {
            return $this->redirectToRoute('app_main');
        }
    }

}

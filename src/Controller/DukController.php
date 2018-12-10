<?php

namespace App\Controller;

use App\Entity\Klausimas;
use App\Entity\KlausimoTipas;
use App\Form\KlausimasType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DukController extends AbstractController
{
    /**
     * @Route("/duk", name="DUK")
     */
    public function index(Request $request)
    {
        $user = $this->getUser();
        $newQuestion = new Klausimas();
        $questionsTypes = $this->getDoctrine()->getRepository(KlausimoTipas::class)->findAll();
        $form = $this->createForm(KlausimasType::class, $newQuestion, array(
            'questionsTypes' => $questionsTypes
        ));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $newQuestion = $form->getData();

            $newQuestion->setParasymoData(new \DateTime());
            $newQuestion->setFkKlausiantysis($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($newQuestion);
            $entityManager->flush();
            $this->addFlash("successful", "Klausimas sėkmingai nusiųstas administratoriams!");
            return $this->redirectToRoute('DUK');

        }
        return $this->render('duk/index.html.twig', [
            'title' => 'Dažnai užduodami klausimai',
            'form' => $form->createView()
        ]);
    }

}

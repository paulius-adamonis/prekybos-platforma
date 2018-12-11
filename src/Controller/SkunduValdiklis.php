<?php

namespace App\Controller;

use App\Entity\Busena;
use App\Entity\Skundas;
use App\Entity\SkundoTipas;
use App\Entity\Vartotojas;
use App\Form\SkundasType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SkunduValdiklis extends AbstractController
{
    /**
     * @Route("/skundai", name="skundai")
     */
    public function index()
    {
        return $this->render('skundu/index.html.twig', [
            'title' => 'Nusiskundimai',
        ]);
    }

    /**
     * @Route("/skundai/naujas", name="skundai_naujas")
     * @Method({"GET", "POST"})
     * @IsGranted("ROLE_USER")
     */
    public function createAction(Request $request)
    {
        $user = $this->getUser();
        $newComplaint = new Skundas();
        $complaints = $this->getDoctrine()->getRepository(SkundoTipas::class)->findAll();
        $status = $this->getDoctrine()->getRepository(Busena::class)->find(1);
        $form = $this->createForm(SkundasType::class, $newComplaint, array(
            'complaints' => $complaints
        ));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $newComplaint = $form->getData();

            $newComplaint->setData(new \DateTime());
            $newComplaint->setFkPareiskejas($user);
            $newComplaint->setFkBusena($status);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($newComplaint);
            $entityManager->flush();

            return $this->render('skundu/index.html.twig', [
                'title' => 'Nusiskundimai',
                'message' => 'Skundas parašytas sėkmingai!'
            ]);

        }
        return $this->render('skundu/naujas.html.twig', [
            'title' => 'Nusiskundimu',
            'forms' => $form->createView()
        ]);
    }

    /**
     * @Route("/skundai/sarasas", name="skundai_sarasas")
     * @Method({"GET", "POST"})
     * @IsGranted("ROLE_USER")
     */
    public function showAction(Request $request)
    {
        $user = $this->getUser();
        $complaints = $user->getParasytusSkundus();

        return $this->render('skundu/sarasas.html.twig', [
            'title' => 'Nusiskundimų sąrašas',
            'complaints' => $complaints
        ]);
    }

    /**
     * @Route("/skundai/sarasas/{id}", name="skundai_sarasas_placiau")
     * @ParamConverter("complaint", class="App\Entity\Skundas", options={"id" = "id"})
     * @Method({"GET", "POST"})
     * @IsGranted("ROLE_USER")
     */
    public function showMoreAction(Skundas $complaint = null)
    {
        $user = $this->getUser();
        if ($complaint !== null && $complaint->getFkPareiskejas() === $user){
            return $this->render('skundu/placiau.html.twig', [
                'title' => 'Peržiūrėti nusiskundimą',
                'skundas' => $complaint
            ]);
        }
        return $this->redirectToRoute('app_main');

    }
}

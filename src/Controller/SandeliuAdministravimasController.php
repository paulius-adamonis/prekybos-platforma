<?php

namespace App\Controller;

use App\Entity\Sandelis;
use App\Form\SandelisType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class SandeliuAdministravimasController
 * @package App\Controller
 * @IsGranted("ROLE_ADMIN")
 */
class SandeliuAdministravimasController extends AbstractController
{
    /**
     * @Route("/admin/sandeliai", name="admin_sandeliai")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $successMessage = null;
        $errorMessage = null;

        $sandelis = new Sandelis();
        $formAdd = $this->createForm(SandelisType::class, $sandelis);

        $formAdd->handleRequest($request);
        $formAdd->getErrors();
        if ($formAdd->isSubmitted() && $formAdd->isValid()) {
            $successMessage = "Sandėlis pridėtas sėkmingai!";
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sandelis);
            $entityManager->flush();
        }
        if ($formAdd->isSubmitted() && !$formAdd->isValid()) {
            $errorMessage = "Sandelio pridėjimas nesėkmingas.";
        }

        $removeId = $request->get('remove');
        if($removeId){
            $sandelisSalinmui = $this->getDoctrine()->getRepository(Sandelis::class)->find($removeId);
            $successMessage = "Sandėlis adresu ".$sandelisSalinmui->getAdresas()." sėkmingai pašalintas!";
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sandelisSalinmui);
            $entityManager->flush();
        }

        $sandeliai = $this->getDoctrine()->getRepository(Sandelis::class)->findAll();

        return $this->render('sandeliu_administravimas/index.html.twig', [
            'title' => 'Sandėlių administravimas',
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage,
            'sandeliai' => $sandeliai,
            'formAdd' => $formAdd->createView()
        ]);
    }
}

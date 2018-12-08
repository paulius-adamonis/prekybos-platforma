<?php

namespace App\Controller;

use App\Entity\Sandelis;
use App\Form\SandelisType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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
     * @Route("/admin/parduotuve/sandeliai", name="admin_parduotuve_sandeliai")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $tmp = null;
        $successMessage = null;
        $errorMessage = null;

        $sandelis = new Sandelis();
        $formAdd = $this->createForm(SandelisType::class, $sandelis);

        $formEdit = $this->createform(SandelisType::class);
        $formEdit->add('edit', HiddenType::class, ['mapped' => false]);
        try{
            $editId = $request->get('sandelis')['edit'];
        } catch (\ErrorException $e){
            $editId = null;
        }

        if($editId){
            /** @var Sandelis $sandelis */
            $sandelis = $this->getDoctrine()->getRepository(Sandelis::class)->find($editId);

            $formEdit->handleRequest($request);
            $formEdit->getErrors();
            if ($formEdit->isSubmitted() && $formEdit->isValid()) {
                $sandelis->setAdresas($request->get('sandelis')['adresas']);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($sandelis);
                $entityManager->flush();
                $successMessage = "Sandėlis redaguotas sėkmingai!";
            }
            if ($formEdit->isSubmitted() && !$formEdit->isValid()) {
                $errorMessage = "Sandelio redagavimas nesėkmingas.";
            }
        } else {
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
        }
        
        $sandeliai = $this->getDoctrine()->getRepository(Sandelis::class)->findAll();

        return $this->render('administravimas/sandeliu_administravimas/index.html.twig', [
            'title' => 'Sandėlių administravimas',
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage,
            'sandeliai' => $sandeliai,
            'formAdd' => $formAdd->createView(),
            'formEdit' => $formEdit->createView()
        ]);
    }
}

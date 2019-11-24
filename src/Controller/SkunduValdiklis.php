<?php

namespace App\Controller;

use App\Entity\Skundas;
use App\Form\SkundasType;
use App\Service\SkundoServisas;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @param Request $request
     * @param SkundoServisas $skundoServisas
     * @return Response
     * @throws Exception
     */
    public function createAction(Request $request, SkundoServisas $skundoServisas)
    {
        //TODO:: Visas kodas nuo pradžios iki response atidavimo turi būti apsuptas TRY-CATCH bloku
        $user = $this->getUser();
        $newComplaint = new Skundas();
        $complaints = $skundoServisas->getAllComplaintsTypes();
        $form = $this->createForm(SkundasType::class, $newComplaint, array(
            'complaints' => $complaints
        ));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $skundoServisas->storeComplaint($newComplaint, $user);

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
    public function showAction()
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

<?php

namespace App\Controller;

use App\Entity\Busena;
use App\Entity\Skundas;
use App\Entity\TurgausPreke;
use App\Entity\Vartotojas;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class VartotojuAdministravimasController
 * @package App\Controller
 * @IsGranted("ROLE_MOD")
 */
class VartotojuAdministravimasController extends AbstractController
{
    /**
     * @Route("/admin/vartotojai/pridetiModeratoriu", name="admin_vartotojai_pridetiModeratoriu")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function pridetiModeratoriu(Request $request)
    {
        $successMessage = null;
        $errorMessage = null;

        $vartotojoId = $request->get('vartotojas');
        if($vartotojoId){
            /** @var Vartotojas $vartotojas */
            $vartotojas = $this->getDoctrine()->getRepository(Vartotojas::class)->find($vartotojoId);
            $vartotojas->addRole("ROLE_MOD");
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vartotojas);
            $entityManager->flush();
            $successMessage = "Moderatorius sėkmingai priskirtas";
        }

        $vartotojai = $this->getDoctrine()->getRepository(Vartotojas::class)->findByNotRoles("ROLE_MOD");

        return $this->render('administravimas/vartotoju_administravimas/pridetiModeratoriu.html.twig', [
            'title' => 'Pridėti naują moderatorių',
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage,
            'vartotojai' => $vartotojai
        ]);
    }

    /**
     * @Route("/admin/vartotojai/salintiModeratoriu", name="admin_vartotojai_salintiModeratoriu")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function salintiModeratoriu(Request $request)
    {
        $successMessage = null;
        $errorMessage = null;

        $vartotojoId = $request->get('vartotojas');
        if($vartotojoId){
            /** @var Vartotojas $vartotojas */
            $vartotojas = $this->getDoctrine()->getRepository(Vartotojas::class)->find($vartotojoId);
            $vartotojas->removeRoles(["ROLE_MOD"]);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vartotojas);
            $entityManager->flush();
            $successMessage = "Moderatorius sėkmingai pašalintas";
        }

        $vartotojai = $this->getDoctrine()->getRepository(Vartotojas::class)->findByRoles("ROLE_MOD");

        return $this->render('administravimas/vartotoju_administravimas/salintiModeratoriu.html.twig', [
            'title' => 'Šalinti moderatorių',
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage,
            'vartotojai' => $vartotojai
        ]);
    }

    /**
     * @Route("/admin/vartotojai/isaktyvuotiVartotoja", name="admin_vartotojai_isaktyvuotiVartotoja")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function isaktyvuotiVartotoja(Request $request)
    {
        $successMessage = null;
        $errorMessage = null;

        $vartotojoId = $request->get('vartotojas');
        if($vartotojoId){
            /** @var Vartotojas $vartotojas */
            $vartotojas = $this->getDoctrine()->getRepository(Vartotojas::class)->find($vartotojoId);
            $vartotojas->setArAktyvus(false);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vartotojas);
            $entityManager->flush();
            $successMessage = "Vartotojas sėkmingai išaktyvuotas";
        }

        $vartotojai = $this->getDoctrine()->getRepository(Vartotojas::class)->findBy(['arAktyvus' => true]);

        return $this->render('administravimas/vartotoju_administravimas/isaktyvuotiVartotoja.html.twig', [
            'title' => 'Išaktyvuoti vartotoją',
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage,
            'vartotojai' => $vartotojai
        ]);
    }

    /**
     * @Route("/admin/vartotojai/nusiskundimai", name="admin_vartotojai_nusiskundimai")
     */
    public function nusiskundimuSarasas(){

        $nusiskundimai = $this->getDoctrine()->getRepository(Skundas::class)->findAll();

        return $this->render('administravimas/vartotoju_administravimas/nusiskundimai.html.twig', [
            'title' => 'Išaktyvuoti vartotoją',
            'skundai' => $nusiskundimai
        ]);
    }

    /**
     * @Route("/admin/vartotojai/keistiNusiskundima/{skundoId}", name="admin_vartotojai_keistiNusiskundima")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function keistiNusiskundima(Request $request, $skundoId)
    {
        $successMessage = null;
        $errorMessage = null;

        /** @var Skundas $skundas */
        $skundas = $this->getDoctrine()->getRepository(Skundas::class)->find($skundoId);

        $form = $this->createFormBuilder($skundas)
            ->add('fkBusena', EntityType::class, [
                'class' => Busena::class,
                'choice_label' => 'pavadinimas',
                'label' => 'Būsena: ',
                'attr' => ['class' => 'form-control']
            ])->getForm();

        $form->handleRequest($request);
        $form->getErrors();
        if ($form->isSubmitted() && $form->isValid()) {
            $skundas->setTikrinimoData(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($skundas);
            $entityManager->flush();
            $successMessage = "Nusiskundimas sėkmingai atnaujintas!";
        }
        if ($form->isSubmitted() && !$form->isValid()) {
            $errorMessage = "Nusiskundimo atnaujinimas nesėkmingas.";
        }

        return $this->render('administravimas/vartotoju_administravimas/keistiNusiskundima.html.twig', [
            'title' => 'Keisti nusiskundimo būseną',
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage,
            'skundas' => $skundas,
            'form' => $form->createView()
        ]);
    }
}

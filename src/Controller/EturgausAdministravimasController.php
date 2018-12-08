<?php

namespace App\Controller;

use App\Entity\Sandelis;
use App\Entity\TurgausPreke;
use App\Entity\TurgPrekesKategorija;
use App\Form\SandelisType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class EturgausAdministravimasController
 * @package App\Controller
 * @IsGranted("ROLE_MOD")
 */
class EturgausAdministravimasController extends AbstractController
{
    /**
     * @Route("/admin/turgus/prekes", name="admin_turgus_prekes")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function prekes(Request $request)
    {
        $successMessage = null;
        $errorMessage = null;

        $prekesId = $request->get('preke');
        if($prekesId){
            /** @var TurgausPreke $preke */
            $preke = $this->getDoctrine()->getRepository(TurgausPreke::class)->find($prekesId);
            $preke->setArPasalinta(true);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($preke);
            $entityManager->flush();
            $successMessage = "Prekė sėkmingai pašalinta";
        }

        $prekes = $this->getDoctrine()->getRepository(TurgausPreke::class)->findBy(['arPasalinta' => false]);

        return $this->render('administravimas/eturgaus_administravimas/prekes.html.twig', [
            'title' => 'Šalinti prekę',
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage,
            'prekes' => $prekes
        ]);
    }

    /**
     * @Route("/admin/turgus/kategorijos", name="admin_turgus_kategorijos")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function kategorijos(Request $request)
    {
        $successMessage = null;
        $errorMessage = null;

        $kategorijosId = $request->get('kategorija');
        if($kategorijosId){
            /** @var TurgPrekesKategorija $kategorija */
            $kategorija = $this->getDoctrine()->getRepository(TurgPrekesKategorija::class)->find($kategorijosId);
            $kategorija->setArPasalinta(true);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($kategorija);
            $entityManager->flush();
            $successMessage = "Kategorija sėkmingai pašalinta";
        }

        $kategorijos = $this->getDoctrine()->getRepository(TurgPrekesKategorija::class)->findBy(['arPasalinta' => false]);

        return $this->render('administravimas/eturgaus_administravimas/kategorijos.html.twig', [
            'title' => 'Šalinti prekę',
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage,
            'kategorijos' => $kategorijos
        ]);
    }
}

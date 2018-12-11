<?php

namespace App\Controller;

use App\Entity\Sandelis;
use App\Entity\SandeliuPriklausymas;
use App\Entity\Vartotojas;
use App\Form\SandelisType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class SandeliuValdytojuAdministravimasController
 * @package App\Controller
 * @IsGranted("ROLE_ADMIN")
 */
class SandeliuValdytojuAdministravimoValdiklis extends AbstractController
{
    /**
     * @Route("/admin/parduotuve/pridetiSandeliuValdytoja", name="admin_parduotuve_pridetiSandeliuValdytoja")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function pridetiSandeliuValdytoja(Request $request)
    {
        $successMessage = null;
        $errorMessage = null;

        $sandeliuValdytojoId = $request->get('sandeliuValdytojas');
        if($sandeliuValdytojoId){
            /** @var Vartotojas $sandeliuValdytojas */
            $sandeliuValdytojas = $this->getDoctrine()->getRepository(Vartotojas::class)->findOneBy(['id' => $sandeliuValdytojoId, 'arAktyvus' => 1]);
            if($sandeliuValdytojas){
                $successMessage = "Sandelio valdytojas sėkmingai pridėtas.";
                $entityManager = $this->getDoctrine()->getManager();
                $sandeliuValdytojas->addRole('ROLE_VALDYTOJAS');
                $entityManager->persist($sandeliuValdytojas);
                $entityManager->flush();
            } else
                $errorMessage = "Sandelio valdytojo pridėjimas nesėkmingas.";
        }

        $vartotojai = $this->getDoctrine()->getRepository(Vartotojas::class)->findByRoles('["ROLE_USER"]');

        return $this->render('administravimas/sandeliuValdytoju_administravimas/pridetiSandeliuValdytoja.html.twig', [
            'title' => 'Pridėti sandelio valdytoją',
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage,
            'vartotojai' => $vartotojai
        ]);
    }

    /**
     * @Route("/admin/parduotuve/sandeliuValdytojuSarasas", name="admin_parduotuve_sandeliuValdytojuSarasas")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sandeliuValdytojuSarasas(Request $request)
    {
        $successMessage = null;
        $errorMessage = null;

        $sandeliuValdytojoId = $request->get('sandeliuValdytojas');
        if($sandeliuValdytojoId){
            /** @var Vartotojas $sandeliuValdytojas */
            $sandeliuValdytojas = $this->getDoctrine()->getRepository(Vartotojas::class)->findOneBy(['id' => $sandeliuValdytojoId, 'arAktyvus' => 1]);
            if($sandeliuValdytojas){
                $successMessage = "Sandelio valdytojas sėkmingai atleistas.";
                $sandeliuValdytojas->removeRoles(['ROLE_VALDYTOJAS']);
                $priklausymas = $this->getDoctrine()->getRepository(SandeliuPriklausymas::class)->findOneBy(['fkVartotojas' => $sandeliuValdytojas]);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($sandeliuValdytojas);
                $entityManager->remove($priklausymas);
                $entityManager->flush();
            } else
                $errorMessage = "Sandelio valdytojo atleidimas nesėkmingas.";
        }

        $sandeliuValdytojai = $this->getDoctrine()->getRepository(Vartotojas::class)->findByRoles('ROLE_VALDYTOJAS');

        return $this->render('administravimas/sandeliuValdytoju_administravimas/sandeliuValdytojuSarasas.html.twig', [
            'title' => 'Perkelti/išmesti sandelio valdytoją',
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage,
            'sandeliuValdytojai' => $sandeliuValdytojai
        ]);
    }

    /**
     * @Route("admin/parduotuve/parinktiSandeliuValdytojuiSandeli/{sandeliuValdytojoId}", name="admin_parduotuve_parinktiSandeliuValdytojuiSandeli")
     * @param Request $request
     * @param $sandeliuValdytojoId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function parinktiSandeliuValdytojuiSandeli(Request $request, $sandeliuValdytojoId){

        $successMessage = null;
        $errorMessage = null;

        $sandelioId = $request->get('sandelis');
        if($sandelioId){
            /** @var Sandelis $sandelis */
            $sandelis = $this->getDoctrine()->getRepository(Sandelis::class)->find($sandelioId);
            /** @var Vartotojas $sandeliuValdytojas */
            $sandeliuValdytojas = $this->getDoctrine()->getRepository(Vartotojas::class)->find($sandeliuValdytojoId);

            /** @var SandeliuPriklausymas $priklausymas */
            $priklausymas = $this->getDoctrine()->getRepository(SandeliuPriklausymas::class)->findOneBy(['fkVartotojas' => $sandeliuValdytojas]);
            if($priklausymas){
                $priklausymas->setFkSandelis($sandelis);
                $successMessage = "Sandelio valdytojas sėkmingai perkeltas";

            } else {
                $priklausymas = new SandeliuPriklausymas();
                $priklausymas->setFkVartotojas($sandeliuValdytojas);
                $priklausymas->setFkSandelis($sandelis);
                $successMessage = "Sandelio valdytojas sėkmingai paskirtas";
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($priklausymas);
            $entityManager->flush();
        }

        $sandeliai = $this->getDoctrine()->getRepository(Sandelis::class)->findAll();

        return $this->render('administravimas/eparduotuves_administravimas/parinktiSandeli.html.twig', [
            'title' => 'Parinkti sandelio valdytojui sandėlį',
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage,
            'sandeliai' => $sandeliai
        ]);
    }
}

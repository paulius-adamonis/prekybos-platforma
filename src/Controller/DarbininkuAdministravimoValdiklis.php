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
 * Class DarbininkuAdministravimasController
 * @package App\Controller
 * @IsGranted("ROLE_ADMIN")
 */
class DarbininkuAdministravimoValdiklis extends AbstractController
{
    /**
     * @Route("/admin/parduotuve/pridetiDarbininka", name="admin_parduotuve_pridetiDarbininka")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function pridetiDarbininka(Request $request)
    {
        $successMessage = null;
        $errorMessage = null;

        $darbininkoId = $request->get('darbininkas');
        if($darbininkoId){
            /** @var Vartotojas $darbinikas */
            $darbinikas = $this->getDoctrine()->getRepository(Vartotojas::class)->findOneBy(['id' => $darbininkoId, 'arAktyvus' => 1]);
            if($darbinikas){
                $successMessage = "Darbininkas sėkmingai pridėtas.";
                $entityManager = $this->getDoctrine()->getManager();
                $darbinikas->addRole('ROLE_DARBININKAS');
                $entityManager->persist($darbinikas);
                $entityManager->flush();
            } else
                $errorMessage = "Darbininko pridėjimas nesėkmingas.";
        }

        $vartotojai = $this->getDoctrine()->getRepository(Vartotojas::class)->findByRoles('["ROLE_USER"]');

        return $this->render('administravimas/darbininku_administravimas/pridetiDarbininka.html.twig', [
            'title' => 'Pridėti darbininką',
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage,
            'vartotojai' => $vartotojai
        ]);
    }

    /**
     * @Route("/admin/parduotuve/darbininkuSarasas", name="admin_parduotuve_darbininkuSarasas")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function darbininkuSarasas(Request $request)
    {
        $successMessage = null;
        $errorMessage = null;

        $darbininkoId = $request->get('darbininkas');
        if($darbininkoId){
            /** @var Vartotojas $darbinikas */
            $darbinikas = $this->getDoctrine()->getRepository(Vartotojas::class)->findOneBy(['id' => $darbininkoId, 'arAktyvus' => 1]);
            if($darbinikas){
                $successMessage = "Darbininkas sėkmingai atleistas.";
                $darbinikas->removeRoles(['ROLE_DARBININKAS']);
                $priklausymas = $this->getDoctrine()->getRepository(SandeliuPriklausymas::class)->findOneBy(['fkVartotojas' => $darbinikas]);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($darbinikas);
                $entityManager->remove($priklausymas);
                $entityManager->flush();
            } else
                $errorMessage = "Darbininko atleidimas nesėkmingas.";
        }

        $darbininkai = $this->getDoctrine()->getRepository(Vartotojas::class)->findByRoles('ROLE_DARBININKAS');

        return $this->render('administravimas/darbininku_administravimas/darbininkuSarasas.html.twig', [
            'title' => 'Perkelti/išmesti darbininką',
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage,
            'darbininkai' => $darbininkai
        ]);
    }

    /**
     * @Route("admin/parduotuve/parinktiDarbininkuiSandeli/{darbininkoId}", name="admin_parduotuve_parinktiDarbininkuiSandeli")
     * @param Request $request
     * @param $darbininkoId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function parinktiDarbininkuiSandeli(Request $request, $darbininkoId){

        $successMessage = null;
        $errorMessage = null;

        $sandelioId = $request->get('sandelis');
        if($sandelioId){
            /** @var Sandelis $sandelis */
            $sandelis = $this->getDoctrine()->getRepository(Sandelis::class)->find($sandelioId);
            /** @var Vartotojas $darbininkas */
            $darbininkas = $this->getDoctrine()->getRepository(Vartotojas::class)->find($darbininkoId);

            /** @var SandeliuPriklausymas $priklausymas */
            $priklausymas = $this->getDoctrine()->getRepository(SandeliuPriklausymas::class)->findOneBy(['fkVartotojas' => $darbininkas]);
            if($priklausymas){
                $priklausymas->setFkSandelis($sandelis);
                $successMessage = "Darbininkas sėkmingai perkeltas";

            } else {
                $priklausymas = new SandeliuPriklausymas();
                $priklausymas->setFkVartotojas($darbininkas);
                $priklausymas->setFkSandelis($sandelis);
                $successMessage = "Darbininkas sėkmingai paskirtas";
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($priklausymas);
            $entityManager->flush();
        }

        $sandeliai = $this->getDoctrine()->getRepository(Sandelis::class)->findAll();

        return $this->render('administravimas/eparduotuves_administravimas/parinktiSandeli.html.twig', [
            'title' => 'Parinkti darbininkui sandėlį',
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage,
            'sandeliai' => $sandeliai
        ]);
    }
}

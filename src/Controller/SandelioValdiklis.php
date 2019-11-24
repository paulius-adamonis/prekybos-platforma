<?php

namespace App\Controller;

use App\Entity\Islaidos;
use App\Entity\IslaidosTipas;
use App\Entity\Kokybe;
use App\Entity\ParduotuvesPreke;
use App\Entity\PrekiuPriklausymas;
use App\Entity\PrekiuUzsakymas;
use App\Entity\Sandelis;
use App\Entity\SandeliuPriklausymas;
use App\Form\SandelioIslaidaType;
use App\Form\SandelisType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class SandelioValdiklis extends AbstractController
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
        elseif ($auth_checker->isGranted('ROLE_DARBININKAS') ||
            $auth_checker->isGranted('ROLE_VALDYTOJAS'))
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
     * @Route("/sandelis/{sandelioId}/islaida", name="islaida")
     */
    public function indexIslaidos($sandelioId)
    {
        $auth_checker = $this->get('security.authorization_checker');
        if($auth_checker->isGranted('ROLE_ADMIN') ||
            $auth_checker->isGranted('ROLE_VALDYTOJAS')) {
            $sandelis = $this->getDoctrine()->getRepository(Sandelis::class)->findOneBy(array(
                'id' => $sandelioId
            ));
            $islaidos = $this->getDoctrine()->getRepository(Islaidos::class)->findBy(array(
                'fkSandelis' => $sandelis
            ));
            if ($sandelis) {
                return $this->render('sandelio/islaidu.html.twig', array(
                    'sandelis' => $sandelis,
                    'title' => 'Išlaidos',
                    'islaidos' => $islaidos,
                    'sandelioId' => $sandelioId));
            } else
                return $this->redirect('/sandelis/' . $sandelioId);
        }
        else {
            return $this->redirectToRoute('app_main');
        }
    }

    /**
     * @Route("/sandelis/{sandelioId}/islaida/new", name="new_islaida")
     * Method({"GET", "POST"})
     */
    public function newIslaida(Request $request, $sandelioId)
    {
        $auth_checker = $this->get('security.authorization_checker');
        if($auth_checker->isGranted('ROLE_ADMIN') ||
            $auth_checker->isGranted('ROLE_VALDYTOJAS')) {
            $islaida = new Islaidos();
            $tipai = $this->getDoctrine()->getRepository(IslaidosTipas::class)->findAll();
            $form = $this->createForm(SandelioIslaidaType::class, $islaida, array(
                'tipai' => $tipai
            ));
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $islaida = $form->getData();
                $sandelis = $this->getDoctrine()->getRepository(Sandelis::class)->findOneBy(
                    array('id' => $sandelioId));
                $islaida
                    ->setFkSandelis($sandelis)
                    ->setData(new \DateTime())
                ;
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($islaida);
                $entityManager->flush();
                return $this->redirect('/sandelis/' . $sandelioId.'/islaida');
            }
            return $this->render('sandelio/islaiduNew.html.twig', array(
                'form' => $form->createView(),
                'title' => 'Pridėti išlaidą į sandėlį'));
        }
        else {
            return $this->redirectToRoute('app_main');
        }
    }

    /**
     * @Route("/sandelis/{sandelioId}/trukstama", name="trukstamos")
     */
    public function indexTrukstamos($sandelioId)
    {
        $auth_checker = $this->get('security.authorization_checker');
        if($auth_checker->isGranted('ROLE_ADMIN') ||
            $auth_checker->isGranted('ROLE_VALDYTOJAS')) {
            $sandelis = $this->getDoctrine()->getRepository(Sandelis::class)->findOneBy(array(
                'id' => $sandelioId
            ));
            $prekes = $this->getDoctrine()->getRepository(PrekiuPriklausymas::class)->findPrekesBySandelisNotDeleted($sandelioId);
            if ($sandelis) {
                return $this->render('sandelio/indexTrukstamos.html.twig', array(
                    'sandelis' => $sandelis,
                    'title' => 'Trukstamos prekės',
                    'prekes' => $prekes,
                    'sandelioId' => $sandelioId));
            } else return $this->redirectToRoute('sandelis');
        }
        else {
            return $this->redirectToRoute('app_main');
        }
    }

    /**
     * @Route("/sandelis/uzsakymai", name="uzsakmai_sandelio")
     */
    public function indexUzsakymai()
    {
        $auth_checker = $this->get('security.authorization_checker');
        if($auth_checker->isGranted('ROLE_ADMIN') ||
            $auth_checker->isGranted('ROLE_VALDYTOJAS')) {
            $userId = $this->getUser()->getId();
            $Uzssakymai = $this->getDoctrine()->getRepository(PrekiuUzsakymas::class)->findBy([
                'fkVartotojas' => $userId
            ]);
            if ($userId) {
                return $this->render('sandelio/indexUzsakymai.html.twig', array(
                    'title' => 'Trukstamos prekės',
                    'uzsakymai' => $Uzssakymai));
            } else return $this->redirectToRoute('sandelis');
        }
        else {
            return $this->redirectToRoute('app_main');
        }
    }

    /**
     * @Route("/sandelis/uzsakymai/{sandelioId}/{prekesId}/{uzsakymoId}/{kiekis}/{busena}", name="busenos")
     *
     */
    public function Busenos($sandelioId,$prekesId,$kiekis,$busena,$uzsakymoId)
    {
        $auth_checker = $this->get('security.authorization_checker');
        if($auth_checker->isGranted('ROLE_ADMIN') ||
            $auth_checker->isGranted('ROLE_VALDYTOJAS')) {
            $sandelis = $this->getDoctrine()->getRepository(Sandelis::class)->findOneBy(['id' => $sandelioId]);
            $preke = $this->getDoctrine()->getRepository(ParduotuvesPreke::class)->findOneBy(['id' => $prekesId]);
            $Uzssakymas = $this->getDoctrine()->getRepository(PrekiuUzsakymas::class)->find($uzsakymoId);
            $prekiuPriklausymai = $this->getDoctrine()->getRepository(PrekiuPriklausymas::class)->findOneBy([
                'fkSandelis' => $sandelis,
                'fkParduotuvesPreke' => $preke
            ]);
            $entityManager = $this->getDoctrine()->getManager();
            if ($busena == 'uzsakyti') {
                $Uzssakymas->setArUzsakyta(true);
                $entityManager->persist($Uzssakymas);
                $entityManager->flush();
                return $this->redirect('/sandelis/uzsakymai');
            } elseif ($busena == 'pristatyta'){
                $Uzssakymas->setArPristatyta(true);
                $entityManager->persist($Uzssakymas);
                $entityManager->flush();
                if(!$prekiuPriklausymai) {
                    $kokybe = $this->getDoctrine()->getRepository(Kokybe::class)->findOneBy(['id' => 1]);
                    $prekesPriklausymas = new PrekiuPriklausymas();
                    $prekesPriklausymas->setKiekis($kiekis)
                        ->setFkKokybe($kokybe)
                        ->setFkSandelis($sandelis)
                        ->setFkParduotuvesPreke($preke);
                    $entityManager->persist($prekesPriklausymas);
                    $entityManager->flush();
                }else{
                    $prekiuPriklausymai = $this->getDoctrine()->getRepository(PrekiuPriklausymas::class)->findOneBy([
                        'fkSandelis' => $sandelis,
                        'fkParduotuvesPreke' => $preke
                    ]);
                    $prekiuPriklausymai->addKiekis($kiekis);
                    $entityManager->merge($prekiuPriklausymai);
                    $entityManager->flush();
                }
                return $this->redirect('/sandelis/uzsakymai');
            }
            else
                return $this->redirect('/sandelis/' . $sandelioId);

        }
        else {
            return $this->redirectToRoute('app_main');
        }
    }


}

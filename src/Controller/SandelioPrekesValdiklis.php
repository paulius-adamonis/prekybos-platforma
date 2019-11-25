<?php

namespace App\Controller;

use App\Entity\Kokybe;
use App\Entity\ParduotuvesPreke;
use App\Entity\PrekiuPriklausymas;
use App\Entity\Sandelis;
use App\Entity\SandeliuPriklausymas;
use App\Form\PrekiuPriklausymasType;
use App\Entity\Islaidos;
use App\Entity\IslaidosTipas;
use App\Entity\PrekiuUzsakymas;
use App\Form\SandelioIslaidaType;
use App\Form\SandelisType;
use App\Form\SandelioPrekesType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class SandelioPrekesValdiklis extends AbstractController
{

    /**
     * @Route("/sandelis", name="sandelis")
     */
    public function indexSandeliai()
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

    /**
     * @Route("/sandelis/{sandelioId}/prekenew", name="new_preke")
     * Method({"GET", "POST"})
     */
    public function new(Request $request, $sandelioId)
    {
        $auth_checker = $this->get('security.authorization_checker');
        if($auth_checker->isGranted('ROLE_ADMIN') ||
            $auth_checker->isGranted('ROLE_VALDYTOJAS')) {
            $prekesPriklausymas = new PrekiuPriklausymas();
            $prekes = $this->getDoctrine()->getRepository(ParduotuvesPreke::class)->findBy(['arPasalinta' => false]);
            $kokybes = $this->getDoctrine()->getRepository(Kokybe::class)->findAll();
            $form = $this->createForm(PrekiuPriklausymasType::class, $prekesPriklausymas, array(
                'kokybes' => $kokybes,
                'prekes' => $prekes
            ));
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $prekesPriklausymas = $form->getData();
                $sandelis = $this->getDoctrine()->getRepository(Sandelis::class)->findOneBy(
                    array('id' => $sandelioId));
                $prekesPriklausymas
                    ->setFkSandelis($sandelis);
                $preke = $prekesPriklausymas->getFkParduotuvesPreke();
                $prekiupriklausymai = $this->getDoctrine()->getRepository(PrekiuPriklausymas::class)->findAll();
                $entityManager = $this->getDoctrine()->getManager();
                $exist = true;
                foreach ($prekiupriklausymai as $priklausymas) {
                    if ($priklausymas->getFkParduotuvesPreke() == $preke &&
                        $priklausymas->getFkSandelis() == $sandelis) {
                        $priklausymas->addKiekis($prekesPriklausymas->getKiekis());
                        $entityManager->merge($priklausymas);
                        $entityManager->flush();
                        $exist = false;
                    }
                }
                if ($exist) {
                    $entityManager->persist($prekesPriklausymas);
                    $entityManager->flush();
                }

                return $this->redirect('/sandelis/' . $sandelioId);
            }
            return $this->render('sandelio_prekes/new.html.twig', array(
                'form' => $form->createView(),
                'title' => 'Pridėti preke į sandėlį'));
        }
        else {
            return $this->redirectToRoute('app_main');
        }
    }

    /**
     * @Route("/sandelis/{sandelioId}/preke/edit/{id}", name="edit_preke")
     * Method({"GET", "POST"})
     */
    public function edit(Request $request,$sandelioId, $id){
        $auth_checker = $this->get('security.authorization_checker');
        if($auth_checker->isGranted('ROLE_ADMIN') ||
            $auth_checker->isGranted('ROLE_VALDYTOJAS')) {
            $prekesPriklausymas = $this->getDoctrine()->getRepository(PrekiuPriklausymas::class)->findOneBy(array(
                'id' => $id
            ));
            if ($prekesPriklausymas !== null) {
                $prekes = $this->getDoctrine()->getRepository(ParduotuvesPreke::class)->findBy(['arPasalinta' => false]);
                $kokybes = $this->getDoctrine()->getRepository(Kokybe::class)->findAll();
                $form = $this->createForm(PrekiuPriklausymasType::class, $prekesPriklausymas, array(
                    'kokybes' => $kokybes,
                    'prekes' => $prekes
                ));
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->flush();
                    return $this->redirect('/sandelis/'.$sandelioId);
                }
                return $this->render('sandelio_prekes/new.html.twig', array(
                    'form' => $form->createView(),
                    'title' => 'Tvarkyti prekę: '.$prekesPriklausymas->getFkParduotuvesPreke()->getPavadinimas().', ID:'.$prekesPriklausymas->getFkParduotuvesPreke()->getId()
                    ));
            }
            return $this->redirect('/sandelis/'.$sandelioId);
        }
        else {
            return $this->redirectToRoute('app_main');
        }
    }

    /**
 * @Route("/sandelis/{sandelioId}/preke/remove/{id}", name="remove_preke")
 * Method({"GET"})
 */
    public function remove($sandelioId,$id)
    {
        $auth_checker = $this->get('security.authorization_checker');
        if ($auth_checker->isGranted('ROLE_ADMIN') ||
            $auth_checker->isGranted('ROLE_VALDYTOJAS')) {
            $preke = $this->getDoctrine()->getRepository(PrekiuPriklausymas::class)->findOneBy(array(
                'id' => $id
            ));
            if ($preke !== null) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($preke);
                $entityManager->flush();
                $this->get('session')->getFlashBag()->add(
                    'successful',
                    'Prekė sėkmingai pašalinta iš sandėlio'
                );
                return $this->redirect('/sandelis/' . $sandelioId);
            }
            return $this->redirect('/sandelis/' . $sandelioId);
        } else {
            return $this->redirectToRoute('app_main');
        }
    }

    /**
     * @Route("/sandelis/{sandelioId}/plusminus/{id}/{number}", name="plusminus")
     *
     */
    public function PlusMinus($sandelioId,$id,$number)
    {
        $auth_checker = $this->get('security.authorization_checker');
        if($auth_checker->isGranted('ROLE_ADMIN') ||
            $auth_checker->isGranted('ROLE_DARBININKAS') ||
            $auth_checker->isGranted('ROLE_VALDYTOJAS')) {
            $prekesPriklausymas = $this->getDoctrine()->getRepository(PrekiuPriklausymas::class)->findOneBy(array(
                'id' =>$id
            ));
            $entityManager = $this->getDoctrine()->getManager();
            if ($number == 1) {
                $prekesPriklausymas->addKiekis(+1);
                $entityManager->merge($prekesPriklausymas);
                $entityManager->flush();
                return $this->redirect('/sandelis/'.$sandelioId);
            }
            elseif ($number == -1){
                $prekesPriklausymas->addKiekis(-1);
                $entityManager->merge($prekesPriklausymas);
                $entityManager->flush();
                return $this->redirect('/sandelis/'.$sandelioId);}
            elseif ($number == 5){
                $prekesPriklausymas->addKiekis(+5);
                $entityManager->merge($prekesPriklausymas);
                $entityManager->flush();
                return $this->redirect('/sandelis/'.$sandelioId);}
            elseif ($number == -5){
                $prekesPriklausymas->addKiekis(-5);
                $entityManager->merge($prekesPriklausymas);
                $entityManager->flush();
                return $this->redirect('/sandelis/'.$sandelioId);
            }else
                return $this->redirect('/sandelis/'.$sandelioId);
        }
        else {
            return $this->redirectToRoute('app_main');
        }
    }

    /**
     * @Route("/sandelis/{sandelioId}/{id}", name="spreke_details")
     *
     */
    public function Show($sandelioId,$id)
    {
        $auth_checker = $this->get('security.authorization_checker');
        if($auth_checker->isGranted('ROLE_ADMIN') ||
            $auth_checker->isGranted('ROLE_DARBININKAS') ||
            $auth_checker->isGranted('ROLE_VALDYTOJAS')) {

            /** @var PrekiuPriklausymas $prekesPriklausymas */
            $prekesPriklausymas = $this->getDoctrine()->getRepository(PrekiuPriklausymas::class)->findOneBy(array(
                'fkSandelis' => $sandelioId,
                'fkParduotuvesPreke' => $id
            ));
            $preke = $this->getDoctrine()->getRepository(ParduotuvesPreke::class)->findOneBy(array(
                'id' => $prekesPriklausymas->getFkParduotuvesPreke()->getId(),
                'arPasalinta' => false
            ));

            if ($preke && $prekesPriklausymas) {
                return $this->render('sandelio_prekes/show.html.twig', array('preke' => $prekesPriklausymas,
                    'title' => 'Prekė'));
            } else
                return $this->redirect('/sandelis/'.$sandelioId);
        }
        else {
            return $this->redirectToRoute('app_main');
        }
    }

    /**
     * @Route("/sandeliuprekes", name="sandeliuprekes")
     */
    public function Prekes()
    {
        $auth_checker = $this->get('security.authorization_checker');
        if($auth_checker->isGranted('ROLE_ADMIN') ||
            $auth_checker->isGranted('ROLE_VALDYTOJAS') ) {
            $prekes = $this->getDoctrine()->getRepository(ParduotuvesPreke::class)->findBy(['arPasalinta' => false]);
            if ($prekes) {
                return $this->render('sandelio_prekes/visosPrekes.html.twig', array(
                    'title' => 'Parduotuvės prekės',
                    'prekes' => $prekes
                ));
            }
        }
        else {
            return $this->redirectToRoute('app_main');
        }
    }


    /**
     * @Route("/sandeliuprekes/{id}", name="preke_details")
     *
     */
    public function ShowPreke($id)
    {
        $auth_checker = $this->get('security.authorization_checker');
        if($auth_checker->isGranted('ROLE_ADMIN') ||
            $auth_checker->isGranted('ROLE_VALDYTOJAS')) {
            $preke = $this->getDoctrine()->getRepository(ParduotuvesPreke::class)->findOneBy(array(
                'id' => $id,
                'arPasalinta' => false
            ));
            if ($preke) {
                return $this->render('sandelio_prekes/show.html.twig', array(
                    'preke' => $preke,
                    'title' => 'Prekė'));
            } else
                return $this->redirectToRoute('sandeliuprekes');
        }
        else {
            return $this->redirectToRoute('app_main');
        }
    }

    /**
     * @Route("/sandelis/{sandelioId}", name="prekes")
     */
    public function index($sandelioId)
    {
        $auth_checker = $this->get('security.authorization_checker');
        if($auth_checker->isGranted('ROLE_ADMIN') ||
            $auth_checker->isGranted('ROLE_DARBININKAS') ||
            $auth_checker->isGranted('ROLE_VALDYTOJAS')) {
            $sandelis = $this->getDoctrine()->getRepository(Sandelis::class)->findOneBy(array(
                'id' => $sandelioId
            ));
            $prekes = $this->getDoctrine()->getRepository(PrekiuPriklausymas::class)->findBy(array(
                'fkSandelis' => $sandelis
            ));
            if ($sandelis) {
                return $this->render('sandelio/show.html.twig', array(
                    'sandelis' => $sandelis,
                    'title' => 'Sandėlis',
                    'prekes' => $prekes,
                    'sandelioId' => $sandelioId));
            } else return $this->redirectToRoute('sandelis');
        }
        else {
            return $this->redirectToRoute('app_main');
        }
    }
}

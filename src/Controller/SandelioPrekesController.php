<?php

namespace App\Controller;

use App\Entity\Kokybe;
use App\Entity\ParduotuvesPreke;
use App\Entity\PrekiuPriklausymas;
use App\Entity\Sandelis;
use App\Entity\SandeliuPriklausymas;
use App\Form\PrekiuPriklausymasType;
use App\Form\SandelioPrekesType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class SandelioPrekesController extends AbstractController
{
    /**
     * @Route("/sandelis/{sandelioId}", name="prekes")
     */
    public function index($sandelioId)
    {
        $auth_checker = $this->get('security.authorization_checker');
        if($auth_checker->isGranted('ROLE_ADMIN') ||
            $auth_checker->isGranted('ROLE_SANDELIO_DARBUOTOJAS') ||
            $auth_checker->isGranted('ROLE_SANDELIO_VALDYTOJAS')) {
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

    /**
     * @Route("/sandelis/{sandelioId}/prekenew", name="new_preke")
     * Method({"GET", "POST"})
     */
    public function new(Request $request, $sandelioId)
    {
        $auth_checker = $this->get('security.authorization_checker');
        if($auth_checker->isGranted('ROLE_ADMIN') ||
            $auth_checker->isGranted('ROLE_SANDELIO_VALDYTOJAS')) {
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
                $exist = false;
                foreach ($prekiupriklausymai as $priklausymas) {
                    if ($priklausymas->getFkParduotuvesPreke() == $preke &&
                        $priklausymas->getFkSandelis() == $sandelis) {
                        $priklausymas->addKiekis($prekesPriklausymas->getKiekis());
                        $entityManager->merge($priklausymas);
                        $entityManager->flush();
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
            $auth_checker->isGranted('ROLE_SANDELIO_VALDYTOJAS')) {
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
                    'title' => 'Tvarkyti prekę'));
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
            $auth_checker->isGranted('ROLE_SANDELIO_VALDYTOJAS')) {
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
            $auth_checker->isGranted('ROLE_SANDELIO_DARBUOTOJAS') ||
            $auth_checker->isGranted('ROLE_SANDELIO_VALDYTOJAS')) {
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
            $auth_checker->isGranted('ROLE_SANDELIO_DARBUOTOJAS') ||
            $auth_checker->isGranted('ROLE_SANDELIO_VALDYTOJAS')) {
            $preke = $this->getDoctrine()->getRepository(ParduotuvesPreke::class)->findOneBy(array(
                'id' => $id,
                'arPasalinta' => false
            ));
            $prekesPriklausymas = $this->getDoctrine()->getRepository(PrekiuPriklausymas::class)->findOneBy(array(
                'fkSandelis' => $sandelioId,
                'fkParduotuvesPreke' => $id
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
        if($auth_checker->isGranted('ROLE_ADMIN') )
            $prekes = $this->getDoctrine()->getRepository(ParduotuvesPreke::class)->findBy(['arPasalinta' => false]);
            if ($prekes) {
                return $this->render('sandelio_prekes/visosPrekes.html.twig', array(
                    'title' => 'Parduotuvės prekės',
                    'prekes' => $prekes
                ));
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
        if($auth_checker->isGranted('ROLE_ADMIN')) {
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
}

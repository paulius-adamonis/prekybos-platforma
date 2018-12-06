<?php

namespace App\Controller;

use App\Entity\PardPrekiuKategorijuPriklausymas;
use App\Entity\ParduotuvesPreke;
use App\Entity\ParduotuvesPrekesKategorija;
use App\Form\ParduotuvesPrekeType;
use App\Service\FileUploader;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class EparduotuvesAdministravimasController
 * @package App\Controller
 * @IsGranted("ROLE_ADMIN")
 */
class EparduotuvesAdministravimasController extends AbstractController
{
    /**
     * @Route("admin/parduotuve/sukurtiPreke", name="admin_parduotuve_sukurtiPreke")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sukurtiPreke(Request $request, FileUploader $fileUploader)
    {
        $successMessage = null;
        $errorMessage = null;

        $preke = new ParduotuvesPreke();
        $form = $this->createForm(ParduotuvesPrekeType::class, $preke);

        $form->handleRequest($request);
        $form->getErrors();
        if ($form->isSubmitted() && $form->isValid()) {
            $preke->setIkelimoData(new \DateTime());
            $nuotrauka = $request->files->get('parduotuves_preke')['nuotrauka'];
            if($nuotrauka){
                $nuotraukosFailas = $fileUploader->upload($nuotrauka);
                $preke->setNuotrauka($nuotraukosFailas);
                $successMessage = "Prekė sėkmingai sukurta!";
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($preke);
                $entityManager->flush();
            }
            else
                $errorMessage = "Prekės sukūrimas nesėkmingas.";
        }
        if($form->isSubmitted() && !$form->isValid())
            $errorMessage = "Prekės sukūrimas nesėkmingas.";

        return $this->render('administravimas/eparduotuves_administravimas/sukurtiPreke.html.twig', [
            'title' => 'Sukurti naują prekę',
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("admin/parduotuve/redaguotiPreke", name="admin_parduotuve_prekiuSarasas")
     */
    public function prekiuSarasas(){
        $prekes = $this->getDoctrine()->getRepository(ParduotuvesPreke::class)->findAll();

        return $this->render('administravimas/eparduotuves_administravimas/prekiuSarasas.html.twig' , [
            'title' => 'Redaguoti prekę',
            'prekes' => $prekes
        ]);
    }

    /**
     * @Route("admin/parduotuve/redaguotiPreke/{prekesId}", name="admin_parduotuve_redaguotiPreke")
     */
    public function redaguotiPreke(Request $request, $prekesId, FileUploader $fileUploader){
        $successMessage = null;
        $errorMessage = null;

        /** @var ParduotuvesPreke $preke */
        $preke = $this->getDoctrine()->getRepository(ParduotuvesPreke::class)->find($prekesId);

        $turimosKategorijos = $this->getDoctrine()->getRepository(ParduotuvesPrekesKategorija::class)->findPavadinimaiByPreke($preke);

        $form = $this->createForm(ParduotuvesPrekeType::class, $preke,['validation_groups' => array('editing')]);
        $form->add('kategorijos', EntityType::class, [
            'class' => ParduotuvesPrekesKategorija::class,
            'choice_label' => 'pavadinimas',
            'multiple' => true,
            'expanded' => true,
            'mapped' => false,
            'label' => 'Kategorijos: '
        ]);

        $form->handleRequest($request);
        if($request->files->get('parduotuves_preke')['nuotrauka'])

        $form->getErrors();
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            //-----------------Kategoriju atnaujinimas-----------------
            $turimosKategorijos = $form['kategorijos']->getData();
            $kategorijuPriklausymai = $this->getDoctrine()->getRepository(PardPrekiuKategorijuPriklausymas::class)
                ->findBy(['fkParduotuvesPreke' => $preke->getId()]);
            //Pridedu naujas
            foreach ($turimosKategorijos as $kategorija){
                $arYra = false;
                /** @var PardPrekiuKategorijuPriklausymas $priklausymas */
                foreach ($kategorijuPriklausymai as $priklausymas){
                    if ($priklausymas->getFkParduotuvesPrekesKategorija() === $kategorija){
                        $arYra = true;
                        break;
                    }
                }
                if(!$arYra){
                    $naujasPriklausymas = new PardPrekiuKategorijuPriklausymas();
                    $naujasPriklausymas->setFkParduotuvesPreke($preke);
                    $naujasPriklausymas->setFkParduotuvesPrekesKategorija($kategorija);
                    $entityManager->persist($naujasPriklausymas);
                }
            }
            //Salinu senus ir nebegaliojancius
            /** @var PardPrekiuKategorijuPriklausymas $priklausymas */
            foreach ($kategorijuPriklausymai as $priklausymas){
                $arYra = false;
                foreach ($turimosKategorijos as $kategorija){
                    if ($priklausymas->getFkParduotuvesPrekesKategorija() === $kategorija){
                        $arYra = true;
                        break;
                    }
                }
                if (!$arYra)
                    $entityManager->remove($priklausymas);
            }
            //---------------------------------------------------------
            $nuotrauka = $request->files->get('parduotuves_preke')['nuotrauka'];
            if($nuotrauka){
                $nuotraukosFailas = $fileUploader->upload($nuotrauka);
                $preke->setNuotrauka($nuotraukosFailas);
                $successMessage = "Prekė sėkmingai pakeista!";
                $entityManager->persist($preke);
                $entityManager->flush();
            }
            else {
                $successMessage = "Prekė sėkmingai pakeista!";
                $entityManager->persist($preke);
                $entityManager->flush();
            }
        }
        if($form->isSubmitted() && !$form->isValid())
            $errorMessage = "Prekės redagavimas nesėkmingas.";

        return $this->render('administravimas/eparduotuves_administravimas/redaguotiPreke.html.twig', [
            'title' => 'Redaguoti prekę',
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage,
            'preke' => $preke,
            'form' => $form->createView(),
            'kategorijos' => $turimosKategorijos
        ]);
    }
}

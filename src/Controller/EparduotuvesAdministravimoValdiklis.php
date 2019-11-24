<?php

namespace App\Controller;

use App\Entity\PardPrekiuKategorijuPriklausymas;
use App\Entity\ParduotuvesPreke;
use App\Entity\ParduotuvesPrekesKategorija;
use App\Entity\PrekiuUzsakymas;
use App\Entity\Sandelis;
use App\Entity\Vartotojas;
use App\Form\ParduotuvesPrekesKategorijaType;
use App\Form\ParduotuvesPrekeType;
use App\Form\PrekiuUzsakymasType;
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
class EparduotuvesAdministravimoValdiklis extends AbstractController
{
    /******************************************
     *                Prekes
     ******************************************/

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

            if(isset($request->files->get('parduotuves_preke')['nuotrauka']))
                $nuotrauka = $request->files->get('parduotuves_preke')['nuotrauka'];
            else
                $nuotrauka = null;

            if($nuotrauka){
                $nuotraukosFailas = $fileUploader->upload($nuotrauka);
                $preke->setNuotrauka($nuotraukosFailas);
                $successMessage = "Prekė sėkmingai sukurta!";
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($preke);
                $entityManager->flush();
            }
            else{
                $preke->setNuotrauka(null);
                $successMessage = "Prekė sėkmingai sukurta!";
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($preke);
                $entityManager->flush();
            }
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
        $successMessage = null;
        $errorMessage = null;

        $prekes = $this->getDoctrine()->getRepository(ParduotuvesPreke::class)->findBy(['arPasalinta' => false]);

        return $this->render('administravimas/eparduotuves_administravimas/prekiuSarasas.html.twig' , [
            'title' => 'Redaguoti prekę',
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage,
            'prekes' => $prekes
        ]);
    }

    /**
     * @Route("admin/parduotuve/redaguotiPreke/{prekesId}", name="admin_parduotuve_redaguotiPreke")
     * @param Request $request
     * @param $prekesId
     * @param FileUploader $fileUploader
     * @return \Symfony\Component\HttpFoundation\Response
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

    /**
     * @Route("admin/parduotuve/salintiPreke/{prekesId}", name="admin_parduotuve_salintiPreke")
     * @param $prekesId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function salintiPreke($prekesId){
        $successMessage = null;
        $errorMessage = null;

        /** @var ParduotuvesPreke $preke */
        $preke = $this->getDoctrine()->getRepository(ParduotuvesPreke::class)->find($prekesId);
        $preke->setArPasalinta(true);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($preke);
        $entityManager->flush();
        $successMessage = 'Prekė sėkmingai pašalinta!';

        $prekes = $this->getDoctrine()->getRepository(ParduotuvesPreke::class)->findBy(['arPasalinta' => false]);

        return $this->render('administravimas/eparduotuves_administravimas/prekiuSarasas.html.twig' , [
            'title' => 'Redaguoti prekę',
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage,
            'prekes' => $prekes
        ]);
    }

    /**
     * @Route("admin/parduotuve/uzsakytiPreke/{prekesId}", name="admin_parduotuve_uzsakytiPreke")
     * @param Request $request
     * @param $prekesId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function uzsakytiPreke(Request $request, $prekesId){
        $successMessage = null;
        $errorMessage = null;

        /** @var ParduotuvesPreke $preke */
        $preke = $this->getDoctrine()->getRepository(ParduotuvesPreke::class)->find($prekesId);
        $sandeliai = $this->getDoctrine()->getRepository(Sandelis::class)->findAll();

        /** @var PrekiuUzsakymas $uzsakymas */
        $uzsakymas = new PrekiuUzsakymas();
        $form = $this->createForm(PrekiuUzsakymasType::class, $uzsakymas);

        $form->handleRequest($request);
        $form->getErrors();
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Sandelis $sandelis */
            $sandelis = $this->getDoctrine()->getRepository(Sandelis::class)->find($request->get('prekiu_uzsakymas')['sandelis']);
            /** @var Vartotojas $valdytojas */
            $valdytojas = $this->getDoctrine()->getRepository(Vartotojas::class)->findValdytojasBySandelis($sandelis);
            if($valdytojas){
                $uzsakymas->setFkSandelis($sandelis);
                $uzsakymas->setFkVartotojas($valdytojas);
                $uzsakymas->setFkParduotuvesPreke($preke);

                $successMessage = "Prekių sėkmingai užsakyta!";
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($uzsakymas);
                $entityManager->flush();
            } else {
                $errorMessage = "Prekių užsakymas nesėkmingas. Pasirinktas sandėlis neturi priskirto valdytojo.";
            }
        }
        if($form->isSubmitted() && !$form->isValid())
            $errorMessage = "Prekių užsakymas nesėkmingas.";

        return $this->render('administravimas/eparduotuves_administravimas/uzsakytiPreke.html.twig' , [
            'title' => 'Užsakyti prekių',
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage,
            'form' => $form->createView(),
            'sandeliai' => $sandeliai
        ]);
    }

    /******************************************
     *              Kategorijos
     ******************************************/

    /**
     * @Route("admin/parduotuve/sukurtiKategorija", name="admin_parduotuve_sukurtiKategorija")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sukurtiKategorija(Request $request){
        $successMessage = null;
        $errorMessage = null;

        $kategorija = new ParduotuvesPrekesKategorija();
        $form = $this->createForm(ParduotuvesPrekesKategorijaType::class, $kategorija);

        $form->handleRequest($request);
        $form->getErrors();
        if ($form->isSubmitted() && $form->isValid()) {
            $successMessage = "Kategorija sėkmingai sukurta!";
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($kategorija);
            $entityManager->flush();
        }
        if($form->isSubmitted() && !$form->isValid())
            $errorMessage = "Kategorijos sukūrimas nesėkmingas.";

        return $this->render('administravimas/eparduotuves_administravimas/sukurtiKategorija.html.twig', [
            'title' => 'Sukurti naują kategoriją',
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("admin/parduotuve/redaguotiKategorija/{kategorijosId}", name="admin_parduotuve_redaguotiKategorija")
     * @param Request $request
     * @param $kategorijosId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function redaguotiKategorija(Request $request, $kategorijosId){
        $successMessage = null;
        $errorMessage = null;

        $kategorija = $this->getDoctrine()->getRepository(ParduotuvesPrekesKategorija::class)->find($kategorijosId);
        $form = $this->createForm(ParduotuvesPrekesKategorijaType::class, $kategorija);

        $form->handleRequest($request);
        $form->getErrors();
        if ($form->isSubmitted() && $form->isValid()) {
            $successMessage = "Kategorija sėkmingai atnaujinta!";
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($kategorija);
            $entityManager->flush();
        }
        if($form->isSubmitted() && !$form->isValid())
            $errorMessage = "Kategorijos atnaujinimas nesėkmingas.";

        return $this->render('administravimas/eparduotuves_administravimas/redaguotiKategorija.html.twig', [
            'title' => 'Redagoti kategoriją',
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("admin/parduotuve/redaguotiKategorija", name="admin_parduotuve_kategorijuSarasas")
     */
    public function kategorijuSarasas(){
        $successMessage = null;
        $errorMessage = null;

        $kategorijos = $this->getDoctrine()->getRepository(ParduotuvesPrekesKategorija::class)->findBy(['arPasalinta' => false]);

        return $this->render('administravimas/eparduotuves_administravimas/kategorijuSarasas.html.twig' , [
            'title' => 'Redaguoti kategoriją',
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage,
            'kategorijos' => $kategorijos
        ]);
    }

    /**
     * @Route("admin/parduotuve/salintiKategorija/{kategorijosId}", name="admin_parduotuve_salintiKategorija")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function salintiKategorija($kategorijosId){
        $successMessage = null;
        $errorMessage = null;

        /** @var ParduotuvesPrekesKategorija $kategorija */
        $kategorija = $this->getDoctrine()->getRepository(ParduotuvesPrekesKategorija::class)->find($kategorijosId);
        $kategorija->setArPasalinta(true);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($kategorija);
        $entityManager->flush();
        $successMessage = 'Kategorija sėkmingai pašalinta!';

        $kategorijos = $this->getDoctrine()->getRepository(ParduotuvesPrekesKategorija::class)->findBy(['arPasalinta' => false]);

        return $this->render('administravimas/eparduotuves_administravimas/kategorijuSarasas.html.twig' , [
            'title' => 'Redaguoti kategoriją',
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage,
            'kategorijos' => $kategorijos
        ]);
    }
}

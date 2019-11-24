<?php
namespace App\Controller;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\PardavimoTipas;
use App\Entity\TurgPrekesKategorija;
use App\Entity\TurgausPreke;
use App\Entity\TurgausPardavimas;
use App\Entity\Vartotojas;
use App\Entity\Komentaras;
use App\Entity\PrekiuNarsymoIstorija;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

class TurgausController extends AbstractController
{
    /**
     * @Route("/turgus/{type}"), methods={"GET", "POST"})
     */
    public function landing($type = 'Įprastas')
    {
        $categoryArr = $this->getDoctrine()->getRepository(PardavimoTipas::class)->findBY(
            array(
                'pavadinimas' => $type
            )
        );

        if ($categoryArr == null) {
            $entityManager = $this->getDoctrine()->getManager();

            $type1 = new PardavimoTipas();
            // $type1->setId(1);
            $type1->setPavadinimas('Įprastas');
            $entityManager->persist($type1);

            $type2 = new PardavimoTipas();
            // $type2->setId(2);
            $type2->setPavadinimas('Aukcionas');
            $entityManager->persist($type2);

            $type3 = new PardavimoTipas();
            // $type3->setId(3);
            $type3->setPavadinimas('Mainai');
            $entityManager->persist($type3);
            
            $entityManager->flush();

            $categoryArr = $this->getDoctrine()->getRepository(PardavimoTipas::class)->findBY(
                array(
                    'pavadinimas' => $type
                )
            );
        }

        $categoryArr = array_values($categoryArr);

        $categories = $this->getDoctrine()->getRepository(TurgPrekesKategorija::class)->findBy(
            array(
                'fkPardavimoTipas' => $categoryArr[0]->getId(),
                'arPasalinta' => 0
            )
        );
        
        $category_count = array();
        $hot_category_count = array();
        $time = new \DateTime();

        foreach($categories as $category) {
            $arr = $this->getDoctrine()->getRepository(TurgausPardavimas::class)->findBy(array());
            $arrFin = array();
            foreach($arr as $elem) {
                if ($elem->getFkPardavejas() == $elem->getFkPirkejas() && $elem->getFkTurgausPreke()->getFkTurgPrekesKategorija() == $category && $elem->getFkTurgausPreke()->isArPasalinta() == 0) {
                    array_push($arrFin, $elem);
                }
            }

            $hotArr = $this->getDoctrine()->getRepository(TurgausPardavimas::class)->findBy(
                array(
                    'data' => $time
                )
            );
            $hotArrFin = array();
            foreach($hotArr as $elem) {
                if($elem->getFkPardavejas() == $elem->getFkPirkejas() && $elem->getFkTurgausPreke()->getFkTurgPrekesKategorija() == $category && $elem->getFkTurgausPreke()->isArPasalinta() == 0) {
                    array_push($hotArrFin, $elem);
                }
            }

            $count = count($arrFin);
            $hotCount = count($hotArrFin);

            array_push($category_count, $count);
            array_push($hot_category_count, $hotCount);
        }

        return $this->render('turgus/landing.twig', [
            'categories' => $categories,
            'selected' => $type,
            'counts' => $category_count,
            'hot_counts' => $hot_category_count
        ]);
    }   

    /**
     * @Route("/turgus/prekes/{type}/{category}/{sort}/{productId}"), methods={"GET"})
     */
    public function products($type = 'Įprastas', $category = '0', $sort, $productId = '-1')
    {
        $typeArr = $this->getDoctrine()->getRepository(PardavimoTipas::class)->findBy(
            array(
                'pavadinimas' => $type
            )
        );

        $typeArr = array_values($typeArr);

        $categoryArr = $this->getDoctrine()->getRepository(TurgPrekesKategorija::class)->findBy(
            array(
                'fkPardavimoTipas' => $typeArr[0]->getId(),
                'pavadinimas' => $category,
                'arPasalinta' => 0
            )
        );

        $sells = $this->getDoctrine()->getRepository(TurgausPardavimas::class)->findBy(array());

        $products = array();

        if($categoryArr != null)
        {
            foreach($sells as $sell) {
                if ($sell->getFkPardavejas() == $sell->getFkPirkejas() && $sell->getFkTurgausPreke()->getFkTurgPrekesKategorija() == $categoryArr[0] && $sell->getFkTurgausPreke()->isArPasalinta() == 0) {
                    array_push($products, $sell->getFkTurgausPreke());
                }
                else if (strtoupper($type) == strtoupper('Aukcionas') && $sell->getFkTurgausPreke()->getFkTurgPrekesKategorija() == $categoryArr[0] && $sell->getFkTurgausPreke()->isArPasalinta() == 0) {
                    array_push($products, $sell->getFkTurgausPreke());
                }
            }

            switch($sort) {
            case '0':
                break;
            case 'mažiausia_kaina':
                for ($i = 0; $i < sizeof($products) - 1; $i++) {
                    for ($j = $i; $j < sizeof($products) - 1; $j++) {
                        if($products[$j]->getKaina() > $products[$j+1]->getKaina()) {
                            $tmp = $products[$j];
                            $products[$j] = $products[$j+1];
                            $products[$j+1] = $tmp;
                        }
                    }
                }
                break;
            case 'didžiausia_kaina':
                for ($i = 0; $i < sizeof($products) - 1; $i++) {
                    for ($j = $i; $j < sizeof($products) - 1; $j++) {
                        if($products[$j]->getKaina() < $products[$j+1]->getKaina()) {
                            $tmp = $products[$j];
                            $products[$j] = $products[$j+1];
                            $products[$j+1] = $tmp;
                        }
                    }
                }
                break;
            case 'naujausi':
                for ($i = 0; $i < sizeof($products) - 1; $i++) {
                    for ($j = $i; $j < sizeof($products) - 1; $j++) {
                        if($products[$j]->getData()->getTimestamp() > $products[$j+1]->getData()->getTimestamp()) {
                            $tmp = $products[$j];
                            $products[$j] = $products[$j+1];
                            $products[$j+1] = $tmp;
                        }
                    }
                }
                break;
            case 'seniausi':
                for ($i = 0; $i < sizeof($products) - 1; $i++) {
                    for ($j = $i; $j < sizeof($products) - 1; $j++) {
                        if($products[$j]->getData()->getTimestamp() < $products[$j+1]->getData()->getTimestamp()) {
                            $tmp = $products[$j];
                            $products[$j] = $products[$j+1];
                            $products[$j+1] = $tmp;
                        }
                    }
                }
                break;
            }

            $selling = array();
            foreach ($products as $product) {
                if ($product->getId() == $productId){
                    $selling = $this->getDoctrine()->getRepository(TurgausPardavimas::class)->findBy(
                        array(
                            'fkTurgausPreke' => $productId
                        )
                    );
                }
            }

            $seller = array();
            if (sizeof($selling) > 0) {
                $seller = $this->getDoctrine()->getRepository(Vartotojas::class)->findBy(
                    array(
                        'id' => $selling[0]->getFkPardavejas()
                    )
                );
            }

            $comments = $this->getDoctrine()->getRepository(Komentaras::class)->findBy(
                array (
                    'fkTurgausPreke' => $productId
                )
            );
            $comments = array_values($comments);

            $commenters = array();
            foreach($comments as $comment) {
                $commenter = $this->getDoctrine()->getRepository(Vartotojas::class)->findBy(
                    array (
                        'id' => $comment->getFkVartotojas()
                    )
                );
                array_push($commenters, $commenter[0]);
            }
            $commenters = array_values($commenters);

            if (sizeof($seller) > 0) {
                $seller = $seller[0];
            } else {
                $seller = '';
            }

            if ($productId != -1){
                if ($seller != $this->getUser()) {
                    $entityManager = $this->getDoctrine()->getManager();
            
                    $visits = $this->getDoctrine()->getRepository(PrekiuNarsymoIstorija::class)->findBy(
                        array (
                            'fkTurgausPreke' => $productId
                        )
                    );
                    
                    if ($visits == null) {
                        $product = $this->getDoctrine()->getRepository(TurgausPreke::class)->findBy(
                            array (
                                'id' => $productId
                            )
                        );
                        $newVisit = new PrekiuNarsymoIstorija();
                        $newVisit->setSkaitliukas(1);
                        $newVisit->setFkVartotojas($this->getUser());
                        $newVisit->setFkTurgausPreke($product[0]);
                        $entityManager->persist($newVisit);
                    } else {
                        $newVisit = $visits[0];
                        $newVisit->setSkaitliukas(intval($newVisit->getSkaitliukas()) + 1);
                        $entityManager->remove($visits[0]);
                        $entityManager->flush();
                        $entityManager->persist($newVisit);
                    }
        
                    $entityManager->flush();
                }
            }
            
            if (strtoupper($type) == strtoupper('Aukcionas') && $productId != '-1') {
                return $this->render('turgus/products.twig', [
                    'selected' => $type,
                    'category' => $categoryArr[0],
                    'products' => $products,
                    'productId' => $productId,
                    'seller' => $seller,
                    'comments' => $comments,
                    'commenters' => $commenters,
                    'bestBetter' => $selling[0]->getFkPirkejas()
                ]);
            }
            
            return $this->render('turgus/products.twig', [
                'selected' => $type,
                'category' => $categoryArr[0],
                'products' => $products,
                'productId' => $productId,
                'seller' => $seller,
                'comments' => $comments,
                'commenters' => $commenters
            ]);
        } else {
            return $this->render('turgus/requestSuccess.twig', [
                'msg' => '404, ar norite grįžti?',
                'link' => '/'
            ]);
        }
    }

    /**
     * @Route("/turgus-mano-prekes/{productId}"), methods={"GET"})
     */
    public function myProducts($productId = -1)
    {
        if ($this->getUser() == null) {
            return $this->render('turgus/requestSuccess.twig', [
                'msg' => 'Prašome prisijungti.',
                'link' => '/login'
            ]);
        }

        $errMsg = "";
        $sellArr = array();
        $productArr = array();
        $user = $this->getUser();

        $sellArr = $this->getDoctrine()->getRepository(TurgausPardavimas::class)->findBy(
            array(
                'fkPardavejas' => $user->getId()
            )
        );

        foreach ($sellArr as $sell) {
            if ($sell->getFkPardavejas() == $sell->getFkPirkejas() && $sell->getFkPardavejas() == $this->getUser() && $sell->getFkTurgausPreke()->isArPasalinta() == 0 && $sell->getFkTurgausPreke()->getFkTurgPrekesKategorija()->isArPasalinta() == 0){
                array_push($productArr, $sell->getFkTurgausPreke());
            } else {
                $category = $this->getDoctrine()->getRepository(TurgPrekesKategorija::class)->findBy(
                    array(
                        'id' => $sell->getFkTurgausPreke()->getFkTurgPrekesKategorija()->getId()
                    )
                );
                if (strtoupper($category[0]->getFkPardavimoTipas()->getPavadinimas()) == strtoupper('Aukcionas') && $sell->getFkPardavejas() == $this->getUser() && $sell->getFkTurgausPreke()->isArPasalinta() == 0 && $sell->getFkTurgausPreke()->getFkTurgPrekesKategorija()->isArPasalinta() == 0){
                    array_push($productArr, $sell->getFkTurgausPreke());
                }
            }
        }

        $seller = $this->getUser();

        $comments = $this->getDoctrine()->getRepository(Komentaras::class)->findBy(
            array (
                'fkTurgausPreke' => $productId
            )
        );
        $comments = array_values($comments);

        $commenters = array();
        foreach($comments as $comment) {
            $commenter = $this->getDoctrine()->getRepository(Vartotojas::class)->findBy(
                array (
                    'id' => $comment->getFkVartotojas()
                )
            );
            array_push($commenters, $commenter[0]);
        }
        $commenters = array_values($commenters);

        return $this->render('turgus/myProducts.twig', [
            'user' => $user,
            'products' => $productArr,
            'errMsg' => $errMsg,
            'productId' => $productId,
            'seller' => $seller,
            'comments' => $comments,
            'commenters' => $commenters
        ]);
    }

    /**
     * @Route("/turgus-nauja-preke/{type}"), methods={"GET", "POST"})
     */
    public function newProduct($type = 0, Request $request)
    {
        if ($this->getUser() == null) {
            return $this->render('turgus/requestSuccess.twig', [
                'msg' => 'Prašome prisijungti.',
                'link' => '/login'
            ]);
        }
        $errMsg = "";
        $sellArr = array();
        $productArr = array();
        $user = $this->getUser();

        if ($user == null) {
            $errorMsg = "Neprisijungęs vartotojas";
        } else {
            $sellArr = $this->getDoctrine()->getRepository(TurgausPardavimas::class)->findBy(
                array(
                    'fkPardavejas' => $user->getId()
                )
            );
        }

        foreach ($sellArr as $sell) {
            if ($sell->getFkPardavejas() == $sell->getFkPirkejas() && $sell->getFkPardavejas() == $this->getUser() && $sell->getFkTurgausPreke()->isArPasalinta() == 0 && $sell->getFkTurgausPreke()->getFkTurgPrekesKategorija()->isArPasalinta() == 0){
                array_push($productArr, $sell->getFkTurgausPreke());
            }
        }

        $sellTypes = $this->getDoctrine()->getRepository(PardavimoTipas::class)->findBy(array());

        $categories = $this->getDoctrine()->getRepository(TurgPrekesKategorija::class)->findBy(array('arPasalinta' => 0, 'fkPardavimoTipas' => $sellTypes[0]->getId()));
        $simpleCategoryChoice = array();
        foreach ($categories as $category) {
            $name = $category->getPavadinimas();
            $id = $category->getId();
            $simpleCategoryChoice[$name] = $id;
        }

        $categories = $this->getDoctrine()->getRepository(TurgPrekesKategorija::class)->findBy(array('arPasalinta' => 0, 'fkPardavimoTipas' => $sellTypes[1]->getId()));
        $auctionCategoryChoice = array();
        foreach ($categories as $category) {
            $name = $category->getPavadinimas();
            $id = $category->getId();
            $auctionCategoryChoice[$name] = $id;
        }

        $categories = $this->getDoctrine()->getRepository(TurgPrekesKategorija::class)->findBy(array('arPasalinta' => 0, 'fkPardavimoTipas' => $sellTypes[2]->getId()));
        $tradeCategoryChoice = array();
        foreach ($categories as $category) {
            $name = $category->getPavadinimas();
            $id = $category->getId();
            $tradeCategoryChoice[$name] = $id;
        }

        $newProduct = new TurgausPreke();
        $newSell = new TurgausPardavimas();

        if($type == '0') {
            $sellTypes = array();
            $sellTypes['Įprastas'] = 'Įprastas';
            $sellTypes['Aukcionas'] = 'Aukcionas';
            $sellTypes['Mainai'] = 'Mainai';

            $form = $this->createFormBuilder(array($newProduct, $newSell))
            ->add('pardavimo_tipas', ChoiceType::class,
                array(
                    'choices' => $sellTypes
                )
            )
            ->add('save', SubmitType::class, array('label' => 'Pridėti prekę'))
            ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $type = $form['pardavimo_tipas']->getData();
                return $this->redirect('/turgus-nauja-preke/'.$type);
            }

            return $this->render('turgus/formCard.twig', [
                'form' => $form->createView()
            ]);
        }

        else if(strtoupper($type) == strtoupper('Aukcionas')) {
            $addCategory = array();
            $form = $this->createFormBuilder(array($newProduct, $newSell))
            ->add('prekes_pavadinimas', TextType::class, array('label' => 'Prekės pavadinimas'))
            ->add('kiekis', IntegerType::class)
            ->add('aprasymas', TextType::class, array('label' => 'Aprašymas'))
            ->add('kategorija', ChoiceType::class, array(
                'choices' => array(
                    'Aukcionas' => $auctionCategoryChoice
                )
            ))
            ->add('nauja_kategorija', ButtonType::class, array (
                'attr' => array(
                    'class' => 'new-category-btn',
                    'onclick' => 'location="/turgus-nauja-kategorija"'
                ),
                'label' => '+'
            ))
            ->add('nuotrauka', FileType::class)
            ->add('pradine_kaina', IntegerType::class, array('label' => 'Pradinė kaina'))
            ->add('maziausias_statymas', IntegerType::class, array('label' => 'Mažiausias statymas'))
            ->add('save', SubmitType::class, array('label' => 'Pridėti prekę'))
            ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $fileName = $form['nuotrauka']->getData()->getClientOriginalName();
                if ( strpos($fileName, '.png') != false){
                    $name = $form['prekes_pavadinimas']->getData();
                    $qty = $form['kiekis']->getData();
                    $about = $form['aprasymas']->getData();
                    $category = $form['kategorija']->getData();
                    $startingPrice = $form['pradine_kaina']->getData();
                    $minBet = $form['maziausias_statymas']->getData();
                    $fileIndex = 0;
                    $fileName = $fileIndex.".png";
                    while (file_exists('images/'.$fileName)) {
                        $fileIndex++;
                        $fileName = $fileIndex.".png";
                    }
                    $file = $form['nuotrauka']->getData();
                    $directory = 'images/';
                    $file->move($directory, $fileName);

                    $category = $this->getDoctrine()->getRepository(TurgPrekesKategorija::class)->findBy(
                        array(
                            'id' => $category
                        )
                    );

                    $type = $category[0]->getFkPardavimoTipas();

                    $newProduct->setPavadinimas($name);
                    $newProduct->setAprasymas($about);
                    $newProduct->setNuotrauka($fileName);
                    $newProduct->setPradineKaina($startingPrice);
                    $newProduct->setData(new \DateTime());
                    $newProduct->setMinimalusStatymas($minBet);
                    $newProduct->setFkTurgPrekesKategorija($category[0]);
                    $newProduct->setArPasalinta(0);
                    $newProduct->setKaina($startingPrice);

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($newProduct);
                    $entityManager->flush();

                    $newProductId = $this->getDoctrine()->getRepository(TurgausPreke::class)->findBy(
                        array(
                            'nuotrauka' => $fileName
                        )
                    );                
                    $newSell->setData(new \DateTime());
                    $newSell->setKiekis($qty);
                    $newSell->setFkPardavejas($this->getUser());
                    $newSell->setFkPirkejas($this->getUser());
                    $newSell->setFkTurgausPreke($newProductId[0]);

                    $entityManager->persist($newSell);
                    $entityManager->flush();


                    return $this->render('turgus/requestSuccess.twig', [
                        'msg' => 'Prekė sėkmingai pridėta į aukcioną.',
                        'link' => '/turgus-mano-prekes'
                    ]);
                }

                return $this->render('turgus/requestSuccess.twig', [
                    'msg' => 'Netinkamas pridėtos nuotraukos plėtinys, turėtų būti .png',
                    'link' => '/turgus-nauja-preke'
                ]);
            }
        }
        else if(strtoupper($type) == strtoupper('Mainai')) {
            $form = $this->createFormBuilder(array($newProduct, $newSell))
            ->add('prekes_pavadinimas', TextType::class, array('label' => 'Prekės pavadinimas'))
            ->add('kiekis', IntegerType::class)
            ->add('aprasymas', TextType::class, array('label' => 'Aprašymas'))
            ->add('kategorija', ChoiceType::class, array(
                'choices' => array(
                    'Mainai' => $tradeCategoryChoice
                )
            ))
            ->add('nauja_kategorija', ButtonType::class, array (
                'attr' => array(
                    'class' => 'new-category-btn',
                    'onclick' => 'location="/turgus-nauja-kategorija"'
                ),
                'label' => '+'
            ))
            ->add('nuotrauka', FileType::class)
            ->add('ieskomos_prekes_aprasymas', TextType::class, array('label' => 'Ieškomos prekės aprašymas'))
            ->add('save', SubmitType::class, array('label' => 'Pridėti prekę'))
            ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $fileName = $form['nuotrauka']->getData()->getClientOriginalName();
                if ( strpos($fileName, '.png') != false){
                    $name = $form['prekes_pavadinimas']->getData();
                    $qty = $form['kiekis']->getData();
                    $about = $form['aprasymas']->getData();
                    $category = $form['kategorija']->getData();
                    $lookingForAbout = $form['ieskomos_prekes_aprasymas']->getData();
                    $fileIndex = 0;
                    $fileName = $fileIndex.".png";
                    while (file_exists('images/'.$fileName)) {
                        $fileIndex++;
                        $fileName = $fileIndex.".png";
                    }
                    $file = $form['nuotrauka']->getData();
                    $directory = 'images/';
                    $file->move($directory, $fileName);

                    $category = $this->getDoctrine()->getRepository(TurgPrekesKategorija::class)->findBy(
                        array(
                            'id' => $category
                        )
                    );

                    $type = $category[0]->getFkPardavimoTipas();

                    $newProduct->setPavadinimas($name);
                    $newProduct->setAprasymas($about);
                    $newProduct->setNuotrauka($fileName);
                    $newProduct->setData(new \DateTime());
                    $newProduct->setIeskomosPrekesAprasymas($lookingForAbout);
                    $newProduct->setFkTurgPrekesKategorija($category[0]);
                    $newProduct->setArPasalinta(0);

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($newProduct);
                    $entityManager->flush();

                    $newProductId = $this->getDoctrine()->getRepository(TurgausPreke::class)->findBy(
                        array(
                            'nuotrauka' => $fileName
                        )
                    );                
                    $newSell->setData(new \DateTime());
                    $newSell->setKiekis($qty);
                    $newSell->setFkPardavejas($this->getUser());
                    $newSell->setFkPirkejas($this->getUser());
                    $newSell->setFkTurgausPreke($newProductId[0]);

                    $entityManager->persist($newSell);
                    $entityManager->flush();


                    return $this->render('turgus/requestSuccess.twig', [
                        'msg' => 'Prekė sėkmingai pridėta į mainus.',
                        'link' => '/turgus-mano-prekes'
                    ]);
                }

                return $this->render('turgus/requestSuccess.twig', [
                    'msg' => 'Netinkamas pridėtos nuotraukos plėtinys, turėtų būti .png',
                    'link' => '/turgus-nauja-preke'
                ]);
            }
        }

        else if(strtoupper($type) == strtoupper('Įprastas')) {
            $form = $this->createFormBuilder(array($newProduct, $newSell))
            ->add('prekes_pavadinimas', TextType::class, array('label' => 'Prekės pavadinimas'))
            ->add('kaina', IntegerType::class)
            ->add('kiekis', IntegerType::class)
            ->add('aprasymas', TextType::class, array('label' => 'Aprašymas'))
            ->add('kategorija', ChoiceType::class, array(
                'choices' => array(
                    'Įprasti pardavimai' => $simpleCategoryChoice
                )
            ))
            ->add('nauja_kategorija', ButtonType::class, array (
                'attr' => array(
                    'class' => 'new-category-btn',
                    'onclick' => 'location="/turgus-nauja-kategorija"'
                ),
                'label' => '+'
            ))
            ->add('nuotrauka', FileType::class)
            ->add('save', SubmitType::class, array('label' => 'Pridėti prekę'))
            ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $fileName = $form['nuotrauka']->getData()->getClientOriginalName();
                if ( strpos($fileName, '.png') != false){
                    $name = $form['prekes_pavadinimas']->getData();
                    $price = $form['kaina']->getData();
                    $qty = $form['kiekis']->getData();
                    $about = $form['aprasymas']->getData();
                    $category = $form['kategorija']->getData();
                    $fileIndex = 0;
                    $fileName = $fileIndex.".png";
                    while (file_exists('images/'.$fileName)) {
                        $fileIndex++;
                        $fileName = $fileIndex.".png";
                    }
                    $file = $form['nuotrauka']->getData();
                    $directory = 'images/';
                    $file->move($directory, $fileName);

                    $category = $this->getDoctrine()->getRepository(TurgPrekesKategorija::class)->findBy(
                        array(
                            'id' => $category
                        )
                    );

                    $type = $category[0]->getFkPardavimoTipas();

                    $newProduct->setPavadinimas($name);
                    $newProduct->setAprasymas($about);
                    $newProduct->setNuotrauka($fileName);
                    $newProduct->setData(new \DateTime());
                    $newProduct->setFkTurgPrekesKategorija($category[0]);
                    $newProduct->setArPasalinta(0);
                    $newProduct->setKaina($price);

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($newProduct);
                    $entityManager->flush();

                    $newProductId = $this->getDoctrine()->getRepository(TurgausPreke::class)->findBy(
                        array(
                            'nuotrauka' => $fileName
                        )
                    );                
                    $newSell->setData(new \DateTime());
                    $newSell->setKiekis($qty);
                    $newSell->setFkPardavejas($this->getUser());
                    $newSell->setFkPirkejas($this->getUser());
                    $newSell->setFkTurgausPreke($newProductId[0]);

                    $entityManager->persist($newSell);
                    $entityManager->flush();


                    return $this->render('turgus/requestSuccess.twig', [
                        'msg' => 'Prekė sėkmingai pridėta į įprastus pardavimus.',
                        'link' => '/turgus-mano-prekes'
                    ]);
                }

                return $this->render('turgus/requestSuccess.twig', [
                    'msg' => 'Netinkamas pridėtos nuotraukos plėtinys, turėtų būti .png',
                    'link' => '/turgus-nauja-preke'
                ]);
            }
        }

        return $this->render('turgus/newProduct.twig', [
            'user' => $user,
            'products' => $productArr,
            'errMsg' => $errMsg,
            'categories' => $categories,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/turgus-pardavimo-registravimas"), methods={"GET", "POST"})
     */
    public function sellRegister(Request $request)
    {
        if ($this->getUser() == null) {
            return $this->render('turgus/requestSuccess.twig', [
                'msg' => 'Prašome prisijungti.',
                'link' => '/login'
            ]);
        }
        if ($this->getUser()->getArAktyvus() == 0) {
            return $this->render('turgus/requestSuccess.twig', [
                'msg' => 'Prašome prisijungti.',
                'link' => '/login'
            ]);
        }
        $errMsg = "";
        $sellArr = array();
        $productArr = array();
        $user = $this->getUser();

        if ($user == null) {
            $errorMsg = "Neprisijungęs vartotojas";
        } else {
            $sellArr = $this->getDoctrine()->getRepository(TurgausPardavimas::class)->findBy(
                array(
                    'fkPardavejas' => $user->getId()
                )
            );
        }

        foreach ($sellArr as $sell) {
            if ($sell->getFkPardavejas() == $sell->getFkPirkejas() && $sell->getFkPardavejas() == $this->getUser() && $sell->getFkTurgausPreke()->isArPasalinta() == 0 && $sell->getFkTurgausPreke()->getFkTurgPrekesKategorija()->isArPasalinta() == 0){
                array_push($productArr, $sell->getFkTurgausPreke());
            } else {
                $category = $this->getDoctrine()->getRepository(TurgPrekesKategorija::class)->findBy(
                    array(
                        'id' => $sell->getFkTurgausPreke()->getFkTurgPrekesKategorija()->getId()
                    )
                );
                if (strtoupper($category[0]->getFkPardavimoTipas()->getPavadinimas()) == strtoupper('Aukcionas') && $sell->getFkPardavejas() == $this->getUser() && $sell->getFkTurgausPreke()->isArPasalinta() == 0 && $sell->getFkTurgausPreke()->getFkTurgPrekesKategorija()->isArPasalinta() == 0){
                    array_push($productArr, $sell->getFkTurgausPreke());
                }
            }
        }

        $productsChoice = array();
        foreach($productArr as $product) {
            $sell = $this->getDoctrine()->getRepository(TurgausPardavimas::class)->findBy(
                array(
                    'fkTurgausPreke' => $product->getId()
                )
            );
            $productsChoice[($product->getPavadinimas())." ".($product->getKaina())."€ kiekis: ".($sell[0]->getKiekis())] =$product->getId();
        }

        $users = $this->getDoctrine()->getRepository(Vartotojas::class)->findBy(
            array(
                'arAktyvus' => 1
            ),
            array(
                'pavarde' => 'ASC'
            )
        );
        $usersChoice = array();
        foreach ($users as $user) {
            $usersChoice[($user->getPavarde())." ".($user->getVardas())] = $user->getId();
        }

        $newProduct = new TurgausPreke();
        $newSell = new TurgausPardavimas();

        $form = $this->createFormBuilder(array())
            ->add('pirkejo_vardas', ChoiceType::class, array(
                'label' => 'Pirkėjo vardas',
                'choices' => $usersChoice
            ))
            ->add('kiekis', IntegerType::class)
            ->add('preke', ChoiceType::class, array(
                'label' => 'Prekė',
                'choices' => $productsChoice
            ))
            ->add('save', SubmitType::class, array('label' => 'Parduoti'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $qty = $form['kiekis']->getData();
            $sell = $this->getDoctrine()->getRepository(TurgausPardavimas::class)->findBy(
                array(
                    'fkTurgausPreke' => $form['preke']->getData()
                )
            );
            if ( $qty <= $sell[0]->getKiekis()){
                $buyerId = $form['pirkejo_vardas']->getData();
                $qty = $form['kiekis']->getData();
                $productId = $form['preke']->getData();

                $updatedSell = $sell[0];
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($sell[0]);

                $product = $this->getDoctrine()->getRepository(TurgausPreke::class)->findBy(
                    array(
                        'id' => $productId
                    )
                );

                $newCount = $updatedSell->getKiekis() - $qty;
                if ($newCount > 0) {
                    $updatedSell->setKiekis($updatedSell->getKiekis() - $qty);
                    $entityManager->persist($updatedSell);
                }

                $regSell = new TurgausPardavimas();
                $regSell->setData(new \DateTime());
                $regSell->setKiekis($qty);
                $regSell->setFkPardavejas($this->getUser());
                $buyer = $this->getDoctrine()->getRepository(Vartotojas::class)->findBy(
                    array(
                        'id' => $buyerId
                    )
                );
                $regSell->setFkPirkejas($buyer[0]);
                $regSell->setFkTurgausPreke($product[0]);

                if (strtoupper($regSell->getFkTurgausPreke()->getFkTurgPrekesKategorija()->getFkPardavimoTipas()->getPavadinimas()) != strtoupper('Aukcionas')) {
                    $entityManager->persist($regSell);
                }
                $entityManager->flush();


                return $this->render('turgus/requestSuccess.twig', [
                    'msg' => 'Pardavimas įregistruotas sėkmingai.',
                    'link' => '/turgus-mano-prekes'
                ]);
            }

            return $this->render('turgus/requestSuccess.twig', [
                'msg' => 'Registruojamas prekių kiekis didesnis nei likęs.',
                'link' => '/turgus-pardavimo-registravimas'
            ]);
        }

        return $this->render('turgus/newProduct.twig', [
            'user' => $user,
            'products' => $productArr,
            'errMsg' => $errMsg,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/turgus-statistika"), methods={"GET"})
     */
    public function statistics() {
        if ($this->getUser() == null) {
            return $this->render('turgus/requestSuccess.twig', [
                'msg' => 'Prašome prisijungti.',
                'link' => '/login'
            ]);
        }

        $sells = $this->getDoctrine()->getRepository(TurgausPardavimas::class)->findBy(
            array(
                'fkPardavejas' => $this->getUser()
            ),
            array(
                'data' => 'ASC'
            )
        );

        $sellsFin = array();
        foreach($sells as $sell) {
            if ($sell->getFkTurgausPreke()->isArPasalinta() == 0 && $sell->getFkTurgausPreke()->getFkTurgPrekesKategorija()->isArPasalinta() == 0) {
                array_push($sellsFin, $sell);
            }
        }
        $sells = $sellsFin;

        $selled = array();
        $uploaded = array();

        for ($i = 0; $i < 12; $i++) {
            $selled[$i] = 0;
            $uploaded[$i] = 0;
        }

        $sellsFin = array();

        foreach ($sells as $sell) {
            $date = $sell->getData();
            $d = date_parse_from_format("Y-m-d", $date->format('Y-m-d H:i:s'));
            if ($sell->getFkPardavejas() == $sell->getFkPirkejas()) {
                if ($sell->getFkTurgausPreke()->getFkTurgPrekesKategorija()->getFkPardavimoTipas()->getId() == 2) {
                    for ($j = $d["month"] - 1; $j < 12; $j++) {
                        $uploaded[$j] += intval($sell->getFkTurgausPreke()->getPradineKaina()) * intval($sell->getKiekis());
                    }
                } else {
                    for ($j = $d["month"] - 1; $j < 12; $j++) {
                        $uploaded[$j] += intval($sell->getFkTurgausPreke()->getKaina()) * intval($sell->getKiekis());
                    }
                }
                array_push($sellsFin, $sell);
            } else {
                if ($sell->getFkTurgausPreke()->getFkTurgPrekesKategorija()->getFkPardavimoTipas()->getId() == 2) {
                    $selled[$d["month"] - 1] += intval($sell->getFkTurgausPreke()->getPradineKaina()) * intval($sell->getKiekis());
                } else {
                    $selled[$d["month"] - 1] += intval($sell->getFkTurgausPreke()->getKaina()) * intval($sell->getKiekis());
                }
            }
        }

        $visitsCounts = array();
        $products = array();

        foreach ($sellsFin as $sell) {
            $visits = $this->getDoctrine()->getRepository(PrekiuNarsymoIstorija::class)->findBy(
                array (
                    'fkTurgausPreke' => $sell->getFkTurgausPreke()
                )
            );
            $visitCount = 0;
            foreach ($visits as $visit) {
                $visitCount += intval($visit->getSkaitliukas());
            }
            array_push($visitsCounts, $visitCount);
            array_push($products, $sell->getFkTurgausPreke());
        }

        return $this->render('turgus/statistics.twig', [
            'selled' => $selled,
            'uploaded' => $uploaded,
            'products' => $products,
            'visits' => $visitsCounts
        ]);
    }
    
    /**
     * @Route("/turgus/prekes/{type}/{category}/{sort}/{productId}/komentaras"), methods={"GET", "POST"})
     */
    public function comment($type = 'Įprastas', $category = '0', $sort, $productId = '-1', Request $request)
    {
        if ($this->getUser() == null) {
            return $this->render('turgus/requestSuccess.twig', [
                'msg' => 'Prašome prisijungti.',
                'link' => '/login'
            ]);
        }

        $typeArr = $this->getDoctrine()->getRepository(PardavimoTipas::class)->findBy(
            array(
                'pavadinimas' => $type
            )
        );

        $typeArr = array_values($typeArr);

        $categoryArr = $this->getDoctrine()->getRepository(TurgPrekesKategorija::class)->findBy(
            array(
                'fkPardavimoTipas' => $typeArr[0]->getId(),
                'pavadinimas' => $category,
                'arPasalinta' => 0
            )
        );

        $sells = $this->getDoctrine()->getRepository(TurgausPardavimas::class)->findBy(array());

        $products = array();

        if($categoryArr != null)
        {
            foreach($sells as $sell) {
                if ($sell->getFkPardavejas() == $sell->getFkPirkejas() && $sell->getFkTurgausPreke()->getFkTurgPrekesKategorija() == $categoryArr[0] && $sell->getFkTurgausPreke()->isArPasalinta() == 0) {
                    array_push($products, $sell->getFkTurgausPreke());
                } else if (strtoupper($type) == strtoupper('Aukcionas')  && $sell->getFkTurgausPreke()->getFkTurgPrekesKategorija() == $categoryArr[0] && $sell->getFkTurgausPreke()->isArPasalinta() == 0) {
                    array_push($products, $sell->getFkTurgausPreke());
                }
            }

            switch($sort) {
            case '0':
                break;
            case 'mažiausia_kaina':
                for ($i = 0; $i < sizeof($products) - 1; $i++) {
                    for ($j = $i; $j < sizeof($products) - 1; $j++) {
                        if($products[$j]->getKaina() > $products[$j+1]->getKaina()) {
                            $tmp = $products[$j];
                            $products[$j] = $products[$j+1];
                            $products[$j+1] = $tmp;
                        }
                    }
                }
                break;
            case 'didžiausia_kaina':
                for ($i = 0; $i < sizeof($products) - 1; $i++) {
                    for ($j = $i; $j < sizeof($products) - 1; $j++) {
                        if($products[$j]->getKaina() < $products[$j+1]->getKaina()) {
                            $tmp = $products[$j];
                            $products[$j] = $products[$j+1];
                            $products[$j+1] = $tmp;
                        }
                    }
                }
                break;
            case 'naujausi':
                for ($i = 0; $i < sizeof($products) - 1; $i++) {
                    for ($j = $i; $j < sizeof($products) - 1; $j++) {
                        if($products[$j]->getData()->getTimestamp() > $products[$j+1]->getData()->getTimestamp()) {
                            $tmp = $products[$j];
                            $products[$j] = $products[$j+1];
                            $products[$j+1] = $tmp;
                        }
                    }
                }
                break;
            case 'seniausi':
                for ($i = 0; $i < sizeof($products) - 1; $i++) {
                    for ($j = $i; $j < sizeof($products) - 1; $j++) {
                        if($products[$j]->getData()->getTimestamp() < $products[$j+1]->getData()->getTimestamp()) {
                            $tmp = $products[$j];
                            $products[$j] = $products[$j+1];
                            $products[$j+1] = $tmp;
                        }
                    }
                }
                break;
            }

            $form = $this->createFormBuilder(array())
            ->add('komentaras', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Komentuoti'))
            ->getForm();

            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) {
                $newComment = new Komentaras();
                $text = $form['komentaras']->getData();
                $date = new \DateTime();
                $commenter = $this->getUser();
                $productsArr = $this->getDoctrine()->getRepository(TurgausPreke::class)->findBy(
                    array(
                        'id' => $productId,
                        'arPasalinta' => 0
                    )
                );
                $product = $productsArr[0];
                
                $newComment->setTekstas($text);
                $newComment->setData($date);
                $newComment->setFkVartotojas($commenter);
                $newComment->setFkTurgausPreke($product);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($newComment);
                $entityManager->flush();

                return $this->render('turgus/requestSuccess.twig', [
                    'msg' => 'Komentaras pridėtas sėkmingai.',
                    'link' => '/turgus/prekes/'.$type.'/'.$category.'/'.$sort
                ]);
            }

            return $this->render('turgus/comment.twig', [
                'selected' => $type,
                'category' => $categoryArr[0],
                'products' => $products,
                'productId' => $productId,
                'form' => $form->createView()
            ]);
        }
        else {
            return $this->render('turgus/requestSuccess.twig', [
                'msg' => '404, ar norite grįžti?',
                'link' => '/'
            ]);
        }
    }

     /**
     * @Route("/turgus-statymo-patvirtinimas/{productId}"), methods={"GET", "POST"})
     */
    public function betConfirm($productId = '-1', Request $request)
    {
        if ($productId == null) {
            return $this->render('turgus/requestSuccess.twig', [
                'msg' => '404, ar norite grįžti?',
                'link' => '/'
            ]);
        }
        if ($this->getUser() == null) {
            return $this->render('turgus/requestSuccess.twig', [
                'msg' => 'Prašome prisijungti.',
                'link' => '/login'
            ]);
        }
        $seller = $this->getDoctrine()->getRepository(TurgausPardavimas::class)->findBy(
            array(
                'fkTurgausPreke' => $productId
            )
        );

        $seller = $seller[0];

        if ($this->getUser() == $seller->getFkPardavejas()) {
            return $this->render('turgus/requestSuccess.twig', [
                'msg' => 'Negalima statyti už savo paties prekę.',
                'link' => '/turgus'
            ]);
        }

        $choices = array();
        $choices["Taip"] = 1;
        $choices["Ne"] = 0;

        $form = $this->createFormBuilder(array())
        ->add('ar_tikrai_norite_pastatyti', ChoiceType::class, array(
            'label' => 'Ar tikrai norite pastatyti?',
            'choices' => $choices
        ))
        ->add('save', SubmitType::class, array('label' => 'Pasirinkti'))
        ->getForm();

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $answer = $form['ar_tikrai_norite_pastatyti']->getData();
            
            if ($answer == '0') {
                return $this->render('turgus/requestSuccess.twig', [
                    'msg' => 'Grįžti atgal?.',
                    'link' => '/turgus'
                ]);
            }

            $sell = $this->getDoctrine()->getRepository(TurgausPardavimas::class)->findBy(
                array(
                    'fkTurgausPreke' => $productId
                )
            );

            $sell = $sell[0];
            $sell->setFkPirkejas($this->getUser());

            $product = $this->getDoctrine()->getRepository(TurgausPreke::class)->findBy(
                array(
                    'id' => $productId
                )
            );

            $product = $product[0];
            $product->setKaina(intval($product->getKaina()) + intval($product->getMinimalusStatymas()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sell);
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->render('turgus/requestSuccess.twig', [
                'msg' => 'Pastatyta sėkmingai.',
                'link' => '/turgus'
            ]);
        }
        
        return $this->render('turgus/formCard.twig', [
            'form' => $form->createView()
        ]);
    }

     /**
     * @Route("/turgus-nauja-kategorija"), methods={"GET", "POST"})
     */
    public function newCategory(Request $request)
    {
        if ($this->getUser() == null) {
            return $this->render('turgus/requestSuccess.twig', [
                'msg' => 'Prašome prisijungti.',
                'link' => '/login'
            ]);
        }

        $types = $this->getDoctrine()->getRepository(PardavimoTipas::class)->findBy(array());
        $choices = array();
        foreach ($types as $t) {
            $choices[$t->getPavadinimas()] = $t->getId();
        }

        $form = $this->createFormBuilder(array())
        ->add('pardavimo_tipas', ChoiceType::class, array(
            'choices' => $choices
        ))
        ->add('kategorijos_pavadinimas', TextType::class)
        ->add('save', SubmitType::class, array('label' => 'Pasirinkti'))
        ->getForm();

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $t = $form['pardavimo_tipas']->getData();
            $name = $form['kategorijos_pavadinimas']->getData();
            $t = $this->getDoctrine()->getRepository(PardavimoTipas::class)->findBy(array('id' => $t));
            $t = $t[0];

            $newCategory = new TurgPrekesKategorija();
            $newCategory->setPavadinimas($name);
            $newCategory->setFkVartotojas($this->getUser());
            $newCategory->setFKPardavimoTipas($t);
            $newCategory->setArPasalinta(0);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($newCategory);
            $entityManager->flush();

            return $this->render('turgus/requestSuccess.twig', [
                'msg' => 'Kategorija pridėta sėkmingai.',
                'link' => '/turgus-mano-prekes'
            ]);
        }

        return $this->render('turgus/formCard.twig', [
            'form' => $form->createView()
        ]);
    }
}
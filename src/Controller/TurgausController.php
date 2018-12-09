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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class TurgausController extends AbstractController
{
    /**
     * @Route("/turgus/{type}"), methods={"GET"})
     */
    public function landing($type = 'Įprastas')
    {
        $categoryArr = $this->getDoctrine()->getRepository(PardavimoTipas::class)->findBY(
            array(
                'pavadinimas' => $type
            )
        );

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

        foreach($sells as $sell) {
            if ($sell->getFkPardavejas() == $sell->getFkPirkejas() && $sell->getFkTurgausPreke()->getFkTurgPrekesKategorija() == $categoryArr[0] && $sell->getFkTurgausPreke()->isArPasalinta() == 0) {
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

        return $this->render('turgus/products.twig', [
            'selected' => $type,
            'category' => $categoryArr[0],
            'products' => $products,
            'productId' => $productId,
            'seller' => $seller,
            'comments' => $comments,
            'commenters' => $commenters
        ]);
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
     * @Route("/turgus-nauja-preke"), methods={"GET", "POST"})
     */
    public function newProduct(Request $request)
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

        $categories = $this->getDoctrine()->getRepository(TurgPrekesKategorija::class)->findBy(array('arPasalinta' => 0, 'fkPardavimoTipas' => 1));
        $simpleCategoryChoice = array();
        foreach ($categories as $category) {
            $name = $category->getPavadinimas();
            $id = $category->getId();
            $simpleCategoryChoice[$name] = $id;
        }

        $categories = $this->getDoctrine()->getRepository(TurgPrekesKategorija::class)->findBy(array('arPasalinta' => 0, 'fkPardavimoTipas' => 2));
        $auctionCategoryChoice = array();
        foreach ($categories as $category) {
            $name = $category->getPavadinimas();
            $id = $category->getId();
            $auctionCategoryChoice[$name] = $id;
        }

        $categories = $this->getDoctrine()->getRepository(TurgPrekesKategorija::class)->findBy(array('arPasalinta' => 0, 'fkPardavimoTipas' => 3));
        $tradeCategoryChoice = array();
        foreach ($categories as $category) {
            $name = $category->getPavadinimas();
            $id = $category->getId();
            $tradeCategoryChoice[$name] = $id;
        }

        $newProduct = new TurgausPreke();
        $newSell = new TurgausPardavimas();

        $form = $this->createFormBuilder(array($newProduct, $newSell))
            ->add('prekes_pavadinimas', TextType::class, array('label' => 'Prekės pavadinimas'))
            ->add('kaina', IntegerType::class)
            ->add('kiekis', IntegerType::class)
            ->add('aprasymas', TextType::class, array('label' => 'Aprašymas'))
            ->add('kategorija', ChoiceType::class, array(
                'choices' => array(
                    'Įprasti pardavimai' => $simpleCategoryChoice,
                    'Aukcionas' => $auctionCategoryChoice,
                    'Mainai' => $tradeCategoryChoice
                )
            ))
            ->add('nuotrauka', FileType::class)
            ->add('pradine_kaina', IntegerType::class, array('label' => 'Pradinė kaina', 'required' => false))
            ->add('maziausias_statymas', IntegerType::class, array('label' => 'Mažiausias statymas', 'required' => false))
            ->add('aukciono_trukme', IntegerType::class, array('label' => 'Aukciono trukmė valandomis', 'required' => false))
            ->add('ieskomos_prekes_aprasymas', TextType::class, array('label' => 'Ieškomos prekės aprašymas', 'required' => false))
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
                $startingPrice = $form['pradine_kaina']->getData();
                $minBet = $form['maziausias_statymas']->getData();
                $auctionLength = $form['aukciono_trukme']->getData();
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
                $newProduct->setPradineKaina($startingPrice);
                $newProduct->setPabaigosData(new \DateTime());
                $newProduct->setData(new \DateTime());
                $newProduct->setMinimalusStatymas($minBet);
                $newProduct->setIeskomosPrekesAprasymas($lookingForAbout);
                $newProduct->setFkTurgPrekesKategorija($category[0]);
                $newProduct->setArPasalinta(0);
                if ($type->getId() == 2) {
                    $newProduct->setKaina($startingPrice);
                } else {
                    $newProduct->setKaina($price);
                }

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
                    'msg' => 'Prekė pridėta sėkmingai.',
                    'link' => '/turgus-mano-prekes'
                ]);
            }

            return $this->render('turgus/requestSuccess.twig', [
                'msg' => 'Netinkamas pridėtos nuotraukos plėtinys, turėtų būti .png',
                'link' => '/turgus-nauja-preke'
            ]);
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
            ->add('save', SubmitType::class, array('label' => 'Pridėti prekę'))
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

                $entityManager->persist($regSell);
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
}
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
            $arr = $this->getDoctrine()->getRepository(TurgausPreke::class)->findBy(
                array(
                    'fkTurgPrekesKategorija' => $category->getId(),
                    'arPasalinta' => 0
                )
            );

            $hotArr = $this->getDoctrine()->getRepository(TurgausPreke::class)->findBy(
                array(
                    'fkTurgPrekesKategorija' => $category->getId(),
                    'data' => $time,
                    'arPasalinta' => 0
                )
            );

            $count = count($arr);
            $hotCount = count($hotArr);

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

        $products = array();

        switch($sort) {
        case '0':
            $products = $this->getDoctrine()->getRepository(TurgausPreke::class)->findBy(
                array(
                    'fkTurgPrekesKategorija' => $categoryArr[0]->getId(),
                    'arPasalinta' => 0
                )
            );
            break;
        case 'mažiausia_kaina':
            $products = $this->getDoctrine()->getRepository(TurgausPreke::class)->findBy(
                array(
                    'fkTurgPrekesKategorija' => $categoryArr[0]->getId(),
                    'arPasalinta' => 0
                ),
                array(
                    'kaina' => 'ASC'
                )
            );
            break;
        case 'didžiausia_kaina':
            $products = $this->getDoctrine()->getRepository(TurgausPreke::class)->findBy(
                array(
                    'fkTurgPrekesKategorija' => $categoryArr[0]->getId(),
                    'arPasalinta' => 0
                ),
                array(
                    'kaina' => 'DESC'
                )
            );
            break;
        case 'naujausi':
            $products = $this->getDoctrine()->getRepository(TurgausPreke::class)->findBy(
                array(
                    'fkTurgPrekesKategorija' => $categoryArr[0]->getId(),
                    'arPasalinta' => 0
                ),
                array(
                    'data' => 'DESC'
                )
            );
            break;
        case 'seniausi':
            $products = $this->getDoctrine()->getRepository(TurgausPreke::class)->findBy(
                array(
                    'fkTurgPrekesKategorija' => $categoryArr[0]->getId(),
                    'arPasalinta' => 0
                ),
                array(
                    'data' => 'ASC'
                )
            );
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
            $product = $this->getDoctrine()->getRepository(TurgausPreke::class)->findBy(
                array(
                    'id' => $sell->getFkTurgausPreke(),
                    'arPasalinta' => 0
                )
            );
            array_push($productArr, $product[0]);
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
            $product = $this->getDoctrine()->getRepository(TurgausPreke::class)->findBy(
                array(
                    'id' => $sell->getFkTurgausPreke(),
                    'arPasalinta' => 0
                )
            );
            array_push($productArr, $product[0]);
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

        $types = $this->getDoctrine()->getRepository(PardavimoTipas::class)->findBy(array());
        $ypeChoice = array();
        foreach ($types as $type) {
            $name = $type->getPavadinimas();
            $id = $type->getId();
            $typeChoice[$name] = $id;
        }

        $newProduct = new TurgausPreke();
        $newSell = new TurgausPardavimas();

        $form = $this->createFormBuilder(array($newProduct, $newSell))
            ->add('prekes_pavadinimas', TextType::class, array('label' => 'Prekės pavadinimas'))
            ->add('kaina', IntegerType::class)
            ->add('kiekis', IntegerType::class)
            ->add('aprasymas', TextType::class, array('label' => 'Aprašymas'))
            ->add('pardavimo_tipas', ChoiceType::class, array(
                'choices' => $typeChoice
            ))
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
                $type = $form['pardavimo_tipas']->getData();
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
                if ($type == 2) {
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
            'types' => $types,
            'form' => $form->createView()
        ]);
    }
}
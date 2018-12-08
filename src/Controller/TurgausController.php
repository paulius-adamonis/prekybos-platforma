<?php
namespace App\Controller;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\PardavimoTipas;
use App\Entity\TurgPrekesKategorija;
use App\Entity\TurgausPreke;
use App\Entity\TurgausPardavimas;
use App\Entity\Vartotojas;
use App\Entity\Komentaras;

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
                'fkPardavimoTipas' => $categoryArr[0]->getId()
            )
        );
        
        $category_count = array();
        $hot_category_count = array();
        $time = new \DateTime();

        foreach($categories as $category) {
            $arr = $this->getDoctrine()->getRepository(TurgausPreke::class)->findBy(
                array(
                    'fkTurgPrekesKategorija' => $category->getId()
                )
            );

            $hotArr = $this->getDoctrine()->getRepository(TurgausPreke::class)->findBy(
                array(
                    'fkTurgPrekesKategorija' => $category->getId(),
                    'data' => $time
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
                'pavadinimas' => $category
            )
        );

        $products = array();

        switch($sort) {
        case '0':
            $products = $this->getDoctrine()->getRepository(TurgausPreke::class)->findBy(
                array(
                    'fkTurgPrekesKategorija' => $categoryArr[0]->getId()
                )
            );
            break;
        case 'mažiausia_kaina':
            $products = $this->getDoctrine()->getRepository(TurgausPreke::class)->findBy(
                array(
                    'fkTurgPrekesKategorija' => $categoryArr[0]->getId()
                ),
                array(
                    'kaina' => 'ASC'
                )
            );
            break;
        case 'didžiausia_kaina':
            $products = $this->getDoctrine()->getRepository(TurgausPreke::class)->findBy(
                array(
                    'fkTurgPrekesKategorija' => $categoryArr[0]->getId()
                ),
                array(
                    'kaina' => 'DESC'
                )
            );
            break;
        case 'naujausi':
            $products = $this->getDoctrine()->getRepository(TurgausPreke::class)->findBy(
                array(
                    'fkTurgPrekesKategorija' => $categoryArr[0]->getId()
                ),
                array(
                    'data' => 'DESC'
                )
            );
            break;
        case 'seniausi':
            $products = $this->getDoctrine()->getRepository(TurgausPreke::class)->findBy(
                array(
                    'fkTurgPrekesKategorija' => $categoryArr[0]->getId()
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
                    'id' => $sell->getFkTurgausPreke()
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
     * @Route("/turgus-nauja-preke"), methods={"GET"})
     */
    public function newProduct()
    {
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
                    'id' => $sell->getFkTurgausPreke()
                )
            );
            array_push($productArr, $product[0]);
        }

        $categories = $this->getDoctrine()->getRepository(TurgPrekesKategorija::class)->findBy(array());
        
        $types = $this->getDoctrine()->getRepository(PardavimoTipas::class)->findBy(array());

        return $this->render('turgus/newProduct.twig', [
            'user' => $user,
            'products' => $productArr,
            'errMsg' => $errMsg,
            'categories' => $categories,
            'types' => $types
        ]);
    }
}
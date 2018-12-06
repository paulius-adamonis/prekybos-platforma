<?php
namespace App\Controller;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\TurgPrekesKategorija;
use App\Entity\TurgausPreke;

class TurgausController extends AbstractController
{
    /**
     * @Route("/turgus/{type}"), methods={"GET"})
     */
    public function landing($type = 0)
    {
        $categories = $this->getDoctrine()->getRepository(TurgPrekesKategorija::class)->findBy(
            array(
                'fkPardavimoTipas' => $type
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
     * @Route("/turgus/prekes/{type}/{category}"), methods={"GET"})
     */
    public function products($type = 0, $category = 0)
    {
        $products = $this->getDoctrine()->getRepository(TurgausPreke::class)->findBy(
            array(
                'fkTurgPrekesKategorija' => $category
            )
        );

        return $this->render('turgus/products.twig', [
            'selected' => $type,
            'products' => $products
        ]);
    }
}
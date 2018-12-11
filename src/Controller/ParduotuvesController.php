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
use App\Entity\ParduotuvesPreke;
use App\Entity\Sandelis;
use App\Entity\PrekiuPriklausymas;
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

class ParduotuvesController extends AbstractController
{
        /**
     * @Route("/parduotuve/{productId}/{sandelisId}", name="parduotuve", methods={"GET"})
     */
    public function index($productId = '-1', $sandelisId = '-1')
    {
        $products = array();
        $product = null;
        $prekSand = null;
        $pard = null;
        $kiekis = 0;
        
        $products = $this->getDoctrine()->getRepository(ParduotuvesPreke::class)->findBy(array());

        if ( $productId != '-1'){
            $pard = $this->getDoctrine()->getRepository(ParduotuvesPreke::class)->findBy(
                array(
                    'id' => $productId
                )
            );

            $priklausimas = $this->getDoctrine()->getRepository(PrekiuPriklausymas::class)->findBy(
                array(
                    'fkParduotuvesPreke' => $productId
                )
            );
            foreach($priklausimas as $value){
                $kiekis += $value->getKiekis();
            }

            $i = 0;
            foreach ($priklausimas as $val){
                $prekSand[$i] = $val->getFkSandelis();
                $i++;
            }
        }
        if ( $sandelisId != '-1' ){
            $products = array();
            $priklausimas = $this->getDoctrine()->getRepository(PrekiuPriklausymas::class)->findBy(
                array(
                    'fkSandelis' => $sandelisId
                )
            );

            $i = 0;
            foreach ($priklausimas as $val){
                $products[$i] = $val->getFkParduotuvesPreke();
                $i++;
            }
        }

        return $this->render('parduotuve/index.html.twig', [
            'title' => 'E-Parduotuve',
            'products' => $products,
            'productId'  => $productId,
            'product' => $pard,
            'sandeliai' => $prekSand,
            'kiekis' => $kiekis
        ]);
    }
}
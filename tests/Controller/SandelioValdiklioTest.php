<?php

namespace App\Tests\Controller;

use App\Entity\Islaidos;
use App\Entity\ParduotuvesPreke;
use App\Entity\PrekiuPriklausymas;
use App\Entity\PrekiuUzsakymas;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManager;

class SandelioValdiklioTest extends WebTestCase
{
    protected $client;

    /** @var EntityManager $em */
    protected $em;

    protected function setUp(){
        parent::setUp();
        $this->client = $this->createClient(array(), array(
            'PHP_AUTH_USER' => 'admin@admin.lt',
            'PHP_AUTH_PW'   => 'admin',
        ));
        $this->client->disableReboot();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->em->beginTransaction();
        $this->em->getConnection()->setAutoCommit(false);
    }

    protected function tearDown(){
        parent::tearDown();
        if ($this->em->getConnection()->isTransactionActive())
        {
            $this->em->rollback();
            $this->em->close();
        }
    }

    private function sukurtiIslaida($kaina, $tipas){
        $crawler = $this->client->request('GET', '/sandelis/3/islaida/new');

        $form = $crawler->selectButton('Patvirtinti')->first()->form();
        $form['sandelio_islaida[kaina]'] = $kaina;
        $form['sandelio_islaida[fkIslaidosTipas]'] = $tipas;

        $this->client->submit($form);
    }

    public function testSukurtiIslaida(){

        $kaina = "500";
        $tipas = 4;

        $this->sukurtiIslaida($kaina, $tipas-1);

        $islaida = $this->em->getRepository(Islaidos::class)->findBy([
            'kaina' => $kaina,
            'fkIslaidosTipas' => $tipas]);
        $this->assertCount(1, $islaida);
    }

    private function pridetiNaujaPrekeISandeli($kiekis, $preke, $kokybe, $sandelis){
        $crawler = $this->client->request('GET', '/sandelis/'.$sandelis.'/prekenew');

        $form = $crawler->selectButton('Patvirtinti')->first()->form();
        $form['prekiu_priklausymas[kiekis]'] = $kiekis;
        $form['prekiu_priklausymas[fkParduotuvesPreke]'] = $preke;
        $form['prekiu_priklausymas[fkKokybe]'] = $kokybe;

        $this->client->submit($form);
    }

    public function testPridetiPreke(){

        $kiekis = "1";
        $preke = 6;
        $kokybe = 0;
        $sandelis = 2;

        $this->pridetiNaujaPrekeISandeli($kiekis, $preke, $kokybe, $sandelis);

        $prekesPeiklausymas = $this->em->getRepository(PrekiuPriklausymas::class)->findBy([
            'fkSandelis' => $sandelis,
            'fkKokybe' => 1,
            'fkParduotuvesPreke' => 15]);
        $this->assertCount(1, $prekesPeiklausymas);
    }

    private function salintiPrekeIsSandelio($sandelioId, $prekesId){
        $crawler = $this->client->request('GET', '/sandelis/'.$sandelioId.'/preke/remove/'.$prekesId);

    }

    public function testSalintiPreke(){

        $kiekis = "1";
        $preke = 6;
        $kokybe = 0;
        $sandelis = 2;

        $this->pridetiNaujaPrekeISandeli($kiekis, $preke, $kokybe, $sandelis);

        /** @var PrekiuPriklausymas $prekesPeiklausymas */
        $prekesPeiklausymas = $this->em->getRepository(PrekiuPriklausymas::class)->findBy([
            'fkSandelis' => $sandelis,
            'fkKokybe' => 1,
            'fkParduotuvesPreke' => 15]);
        $this->assertCount(1, $prekesPeiklausymas);

        $this->salintiPrekeIsSandelio($sandelis, $prekesPeiklausymas[0]->getId());

        $prekesPeiklausymas = $this->em->getRepository(PrekiuPriklausymas::class)->findBy([
            'fkSandelis' => $sandelis,
            'fkKokybe' => 1,
            'fkParduotuvesPreke' => 15]);
        $this->assertCount(0, $prekesPeiklausymas);
    }

    private function redaguotiPreke($kiekis, $preke, $kokybe, $sandelioId, $prekesId){
        $crawler = $this->client->request('GET', '/sandelis/'.$sandelioId.'/preke/edit/'.$prekesId);

        $form = $crawler->selectButton('Patvirtinti')->first()->form();
        $form['prekiu_priklausymas[kiekis]'] = $kiekis;
        $form['prekiu_priklausymas[fkParduotuvesPreke]'] = $preke;
        $form['prekiu_priklausymas[fkKokybe]'] = $kokybe;

        $this->client->submit($form);
    }

    public function testRedaguotiPreke(){

        $kiekis1 = "1";
        $kiekis2 = "100";
        $preke = 6;
        $kokybe = 0;
        $sandelis = 2;

        $this->pridetiNaujaPrekeISandeli($kiekis1, $preke, $kokybe, $sandelis);

        /** @var PrekiuPriklausymas $prekesPeiklausymas */
        $prekesPeiklausymas = $this->em->getRepository(PrekiuPriklausymas::class)->findBy([
            'fkSandelis' => $sandelis,
            'fkKokybe' => 1,
            //'kiekis' => $kiekis1,
            'fkParduotuvesPreke' => 15]);
        $this->assertCount(1, $prekesPeiklausymas);

        $this->redaguotiPreke($kiekis2, $preke, $kokybe, $sandelis, $prekesPeiklausymas[0]->getId());

        $prekesPeiklausymas = $this->em->getRepository(PrekiuPriklausymas::class)->findBy([
            'fkSandelis' => $sandelis,
            'fkKokybe' => 1,
            'kiekis' => $kiekis2,
            'fkParduotuvesPreke' => 15]);
        $this->assertCount(1, $prekesPeiklausymas);
    }

    private function KeistiUzsakymoBusena($sandelioId, $prekesId ,$kiekis, $uzsakymoId, $busena){
        $crawler = $this->client->request('GET', '/sandelis/uzsakymai/'.$sandelioId.'/'.$prekesId.'/'.$uzsakymoId.'/'.$kiekis.'/'.$busena);
    }

    public function testUzsakytiPreke(){

        $kiekis = 3;
        $preke = 15;
        $sandelis = 3;
        $uzsakymoId = 7;
        $busena = 'uzsakyti';


        ///** @var PrekiuPriklausymas $prekesPeiklausymas */
        $prekesPeiklausymas = $this->em->getRepository(PrekiuUzsakymas::class)->findBy([
            'fkSandelis' => $sandelis,
            'fkParduotuvesPreke' => $preke,
            'kiekis' => $kiekis,
            'arPristatyta' => false,
            'arUzsakyta' => false]);
        $this->assertCount(1, $prekesPeiklausymas);

        $this->KeistiUzsakymoBusena($sandelis, $preke, $kiekis, $uzsakymoId, $busena);

        $prekesPeiklausymas = $this->em->getRepository(PrekiuUzsakymas::class)->findBy([
            'fkSandelis' => $sandelis,
            'fkParduotuvesPreke' => $preke,
            'kiekis' => $kiekis,
            'arPristatyta' => false,
            'arUzsakyta' => true]);
        $this->assertCount(1, $prekesPeiklausymas);

        $busena = 'pristatyta';
        $this->KeistiUzsakymoBusena($sandelis, $preke, $kiekis, $uzsakymoId, $busena);

        $prekesPeiklausymas = $this->em->getRepository(PrekiuUzsakymas::class)->findBy([
            'fkSandelis' => $sandelis,
            'fkParduotuvesPreke' => $preke,
            'kiekis' => $kiekis,
            'arPristatyta' => true,
            'arUzsakyta' => true]);
        $this->assertCount(1, $prekesPeiklausymas);
    }

    public function testUzsakytiPrekeKaiPrekeYraSandelyje(){

        $kiekis = 55;
        $preke = 15;
        $sandelis = 3;
        $uzsakymoId = 8;
        $busena = 'uzsakyti';

        $this->pridetiNaujaPrekeISandeli(1, 6, 0, $sandelis);

        /** @var PrekiuPriklausymas $prekesPeiklausymas */
        $prekesPeiklausymas = $this->em->getRepository(PrekiuPriklausymas::class)->findBy([
            'fkSandelis' => $sandelis,
            'fkKokybe' => 1,
            'fkParduotuvesPreke' => 15]);
        $this->assertCount(1, $prekesPeiklausymas);

        ///** @var PrekiuPriklausymas $prekesPeiklausymas */
        $prekesPeiklausymas = $this->em->getRepository(PrekiuUzsakymas::class)->findBy([
            'fkSandelis' => $sandelis,
            'fkParduotuvesPreke' => $preke,
            'kiekis' => $kiekis,
            'arPristatyta' => false,
            'arUzsakyta' => false]);
        $this->assertCount(1, $prekesPeiklausymas);

        $this->KeistiUzsakymoBusena($sandelis, $preke, $kiekis, $uzsakymoId, $busena);

        $prekesPeiklausymas = $this->em->getRepository(PrekiuUzsakymas::class)->findBy([
            'fkSandelis' => $sandelis,
            'fkParduotuvesPreke' => $preke,
            'kiekis' => $kiekis,
            'arPristatyta' => false,
            'arUzsakyta' => true]);
        $this->assertCount(1, $prekesPeiklausymas);

        $busena = 'pristatyta';
        $this->KeistiUzsakymoBusena($sandelis, $preke, $kiekis, $uzsakymoId, $busena);

        $prekesPeiklausymas = $this->em->getRepository(PrekiuUzsakymas::class)->findBy([
            'fkSandelis' => $sandelis,
            'fkParduotuvesPreke' => $preke,
            'kiekis' => $kiekis,
            'arPristatyta' => true,
            'arUzsakyta' => true]);
        $this->assertCount(1, $prekesPeiklausymas);

        $prekesPeiklausymas = $this->em->getRepository(PrekiuPriklausymas::class)->findBy([
            'fkSandelis' => $sandelis,
            'fkKokybe' => 1,
            'kiekis' => 1+$kiekis,
            'fkParduotuvesPreke' => 15]);
        $this->assertCount(1, $prekesPeiklausymas);
    }
}

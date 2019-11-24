<?php

namespace App\Tests\Controller;

use App\Entity\ParduotuvesPreke;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManager;

class EparduotuvesAdministravimoValdiklioTest extends WebTestCase
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

    private function sukurtiPreke($pavadinimas, $aprasymas){
        $crawler = $this->client->request('GET', '/admin/parduotuve/sukurtiPreke');

        $form = $crawler->selectButton('Sukurti!')->first()->form();
        $form['parduotuves_preke[pavadinimas]'] = $pavadinimas;
        $form['parduotuves_preke[aprasymas]'] = $aprasymas;

        $this->client->submit($form);
    }

    public function testSukurtiPreke(){
        $pavadinimas = "Testo prekes pavadinimas";
        $aprasymas = "Testo prekes aprasymas";

        $this->sukurtiPreke($pavadinimas, $aprasymas);

        $this->assertContains('Pavyko!', $this->client->getResponse()->getContent());

        $preke = $this->em->getRepository(ParduotuvesPreke::class)->findBy([
            'pavadinimas' => $pavadinimas,
            'aprasymas' => $aprasymas]);
        $this->assertCount(1, $preke);
    }
}

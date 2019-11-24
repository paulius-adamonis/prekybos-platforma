<?php

namespace App\Tests\Service;

use App\Entity\Busena;
use App\Entity\Skundas;
use App\Entity\SkundoTipas;
use App\Entity\Vartotojas;
use App\Repository\SkundoTipasRepository;
use App\Service\BusenosServisas;
use App\Service\SkundoServisas;
use App\Utils\DateTimeUtil;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SkundoServisasTest extends TestCase
{
    /**
     * @return array
     * @throws Exception
     */
    public function getStoredTestCaseData()
    {
        $out = [];
        $statusFromRepository = new Busena();
        $statusFromRepository
            ->setId(1)
            ->setPavadinimas("Pirmas testas");

        $user = new Vartotojas();
        $data = "2018-04-08 11:59:00";
        $newComplaint = new Skundas();
        $newComplaint->setSkundas("skundas")
            ->setTikrinimoData(null);

        $expected = new Skundas();
        $expected->setSkundas("skundas")
            ->setTikrinimoData(null)
            ->setData(new DateTime($data))
            ->setFkBusena($statusFromRepository)
            ->setFkPareiskejas($user);

        $out['FirstTest'] = [
            $expected,
            $statusFromRepository,
            $newComplaint,
            $user,
            $data
        ];
        return $out;
    }

    /**
     * @dataProvider getStoredTestCaseData
     * @param Skundas $expected
     * @param Busena $statusFromRepo
     * @param Skundas $newComplaint
     * @param Vartotojas $user
     * @param string $date
     * @throws Exception
     */
    public function testStoreComplaint(
        Skundas $expected,
        Busena $statusFromRepo,
        Skundas $newComplaint,
        Vartotojas $user,
        string $date
    ) {
        $statusService = new SkundoServisas(
            $this->getStatusServiceMock($statusFromRepo),
            $this->getEntityManagerMock(null),
            $this->getDateTimeUtilMock($date)
        );

        $this->assertEquals($expected, $statusService->storeComplaint($newComplaint, $user));
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getAllComplaintsTypesTestCaseData()
    {
        $out = [];
        $expected = $complaintsFromRep = [
            (new SkundoTipas())
                ->setPavadinimas("pirmas tipas")->setAprasas("pirmas aprašas"),
            (new SkundoTipas())
                ->setPavadinimas("antras tipas")->setAprasas("antras aprašas")
        ];

        $out['SecondTest'] = [
            $expected,
            $complaintsFromRep
        ];

        return $out;
    }

    /**
     * @dataProvider getAllComplaintsTypesTestCaseData
     * @param SkundoTipas[] $expected
     * @param SkundoTipas[] $complaintsType
     * @throws Exception
     */
    public function testGetAllComplaintsTypes(
        array $expected,
        array $complaintsType
    ) {
        $statusService = new SkundoServisas(
            $this->getStatusServiceMock(null),
            $this->getEntityManagerMock($complaintsType),
            $this->getDateTimeUtilMock(null)
        );

        $this->assertEquals($expected, $statusService->getAllComplaintsTypes());
    }

    /**
     * @param SkundoTipas[]|null $allComplaints
     * @return EntityManagerInterface|MockObject
     */
    private function getEntityManagerMock(?array $allComplaints)
    {
        $mock = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        if ($allComplaints !== null) {
            $mock->expects($this->at(1))->method('getRepository')
                ->with($this->equalTo(SkundoTipas::class))
                ->willReturn($this->getRepositoryMock($allComplaints));
        }

        return $mock;
    }

    /**
     * @param SkundoTipas[]|null $allComplaints
     * @return SkundoTipasRepository|MockObject
     */
    private function getRepositoryMock(?array $allComplaints)
    {
        $mock = $this->getMockBuilder(SkundoTipasRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mock->expects($this->any())
            ->method('findAll')
            ->willReturn($allComplaints);

        return $mock;
    }

    /**
     * @param Busena|null $statusFromRepo
     * @return BusenosServisas|MockObject
     */
    private function getStatusServiceMock(?Busena $statusFromRepo)
    {
        $mock = $this->getMockBuilder(BusenosServisas::class)
            ->disableOriginalConstructor()
            ->getMock();
        if ($statusFromRepo !== null) {
            $mock->expects($this->once())
                ->method('getFirstStatus')
                ->willReturn($statusFromRepo);
        }

        return $mock;
    }

    /**
     * @param string|null $time
     * @return DateTimeUtil|MockObject
     * @throws Exception
     */
    private function getDateTimeUtilMock(?string $time)
    {
        $mock = $this->getMockBuilder(DateTimeUtil::class)->disableOriginalConstructor()->getMock();
        if ($time !== null) {
            $mock->expects($this->any())
                ->method('getCurrentDataTime')
                ->willReturn(new DateTime($time));
        }

        return $mock;
    }
}

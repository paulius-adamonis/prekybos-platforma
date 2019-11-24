<?php

namespace App\Tests\Service;

use App\Entity\Busena;
use App\Repository\BusenaRepository;
use App\Service\BusenosServisas;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BusenosServisasTest extends TestCase
{
    /**
     * @return array
     */
    public function getTestCaseData()
    {
        $out = [];
        $expected = new Busena();
        $statusFromRepository = $expected
            ->setId(1)
            ->setPavadinimas("Pirmas testas");
        $out['FirstTest'] = [
            $expected,
            $statusFromRepository
        ];
        return $out;
    }

    /**
     * @dataProvider getTestCaseData
     * @param Busena $expected
     * @param Busena $statusFromRepo
     */
    public function testGetFirstStatus(Busena $expected, Busena $statusFromRepo)
    {
        $statusService = new BusenosServisas($this->getEntityManagerMock($statusFromRepo));

        $this->assertEquals($expected, $statusService->getFirstStatus());
    }

    /**
     * @param Busena $statusFromRepo
     * @return EntityManagerInterface|MockObject
     */
    private function getEntityManagerMock(Busena $statusFromRepo)
    {
        $mock = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mock->expects($this->once())->method('getRepository')
            ->with($this->equalTo(Busena::class))
            ->willReturn($this->getRepositoryMock($statusFromRepo));

        return $mock;
    }

    /**
     * @param Busena $statusFromRepo
     * @return BusenaRepository|MockObject
     */
    private function getRepositoryMock(Busena $statusFromRepo)
    {
        $mock = $this->getMockBuilder(BusenaRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mock->expects($this->any())
            ->method('find')
            ->with(1)
            ->willReturn($statusFromRepo);

        return $mock;
    }
}

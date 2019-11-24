<?php

namespace App\Service;

use App\Entity\Skundas;
use App\Entity\SkundoTipas;
use App\Entity\Vartotojas;
use App\Utils\DateTimeUtil;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class SkundoServisas extends BazineServisoStruktura
{
    /**
     * @var BusenosServisas $statusService
     */
    private $statusService;

    /**
     * @var DateTimeUtil $dateTimeUtil
     */
    private $dateTimeUtil;

    /**
     * SkundoServisas constructor.
     * @param BusenosServisas $statusService
     * @param EntityManagerInterface $entityManager
     * @param DateTimeUtil $dateTimeUtil
     */
    public function __construct(
        BusenosServisas $statusService,
        EntityManagerInterface $entityManager,
        DateTimeUtil $dateTimeUtil
    ) {
        $this->statusService = $statusService;
        $this->dateTimeUtil = $dateTimeUtil;
        parent::__construct($entityManager);
    }

    /**
     * @return SkundoTipas[]|object[]
     */
    public function getAllComplaintsTypes()
    {
        return $this->getRepository(SkundoTipas::class)->findAll();
    }

    /**
     * @param Skundas $newComplaint
     * @param Vartotojas $user
     * @return Skundas
     * @throws Exception
     */
    public function storeComplaint(Skundas $newComplaint, Vartotojas $user)
    {
        $newComplaint->setData($this->dateTimeUtil->getCurrentDataTime());
        $newComplaint->setFkPareiskejas($user);
        $newComplaint->setFkBusena($this->statusService->getFirstStatus());
        $this->persist($newComplaint);
        $this->flush();

        return $newComplaint;
    }

    /**
     * @return string
     */
    public function getEntityClass()
    {
        return Skundas::class;
    }
}

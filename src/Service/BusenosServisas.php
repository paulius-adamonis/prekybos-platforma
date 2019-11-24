<?php

namespace App\Service;

use App\Entity\Busena;
use App\Repository\BusenaRepository;

class BusenosServisas extends BazineServisoStruktura
{
    /**
     * Iš principo nebūtinas, bet taip galima sutvarkyti anotacijas
     * Ir padėjus -> bus siūlomi variantai iš konkrečios repositorijos,/
     * Tuo pačiu bus gauti geri rezultatai ir return bus teisingas kaip sakant susiriša visi Type'ai.
     * @var BusenaRepository $repository
     */
    protected $repository;

    /**
     * @return Busena|null
     */
    public function getFirstStatus()
    {
        return $this->repository->find(1);
    }

    /**
     * @return string
     */
    public function getEntityClass()
    {
        return Busena::class;
    }
}

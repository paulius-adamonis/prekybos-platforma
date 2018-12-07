<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ParduotuvesPrekesKategorija
 *
 * @ORM\Table(name="PARDUOTUVES_PREKES_KATEGORIJA", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\ParduotuvesPrekesKategorijaRepository")
 */
class ParduotuvesPrekesKategorija
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="pavadinimas", type="string", length=30, nullable=false)
     */
    private $pavadinimas;

    /**
     * @var bool
     *
     * @ORM\Column(name="ar_pasalinta", type="boolean", nullable=false, options={"default"="0"})
     */
    private $arPasalinta = '0';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPavadinimas(): ?string
    {
        return $this->pavadinimas;
    }

    public function setPavadinimas(string $pavadinimas): self
    {
        $this->pavadinimas = $pavadinimas;

        return $this;
    }

    /**
     * @return bool
     */
    public function isArPasalinta(): bool
    {
        return $this->arPasalinta;
    }

    /**
     * @param bool $arPasalinta
     */
    public function setArPasalinta(bool $arPasalinta): void
    {
        $this->arPasalinta = $arPasalinta;
    }


}

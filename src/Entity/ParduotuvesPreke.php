<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;

/**
 * ParduotuvesPreke
 *
 * @ORM\Table(name="PARDUOTUVES_PREKE", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\ParduotuvesPrekeRepository")
 */
class ParduotuvesPreke
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
     * @var string
     *
     * @ORM\Column(name="aprasymas", type="string", length=300, nullable=false)
     */
    private $aprasymas;

    /**
     *
     * @ORM\Column(name="nuotrauka", type="string", length=300, nullable=true)
     */
    private $nuotrauka;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ikelimo_data", type="date", nullable=false)
     */
    private $ikelimoData;

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

    public function getAprasymas(): ?string
    {
        return $this->aprasymas;
    }

    public function setAprasymas(string $aprasymas): self
    {
        $this->aprasymas = $aprasymas;

        return $this;
    }

    public function getNuotrauka()
    {
        if($this->nuotrauka)
            return new File('../public/images/'.$this->nuotrauka);
        else
            return null;
    }

    public function setNuotrauka($nuotrauka)
    {
        $this->nuotrauka = $nuotrauka;

        return $this;
    }

    public function getIkelimoData(): ?\DateTimeInterface
    {
        return $this->ikelimoData;
    }

    public function setIkelimoData(\DateTimeInterface $ikelimoData): self
    {
        $this->ikelimoData = $ikelimoData;

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

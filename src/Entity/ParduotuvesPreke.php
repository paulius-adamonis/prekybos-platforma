<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @var string|null
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

    public function getNuotrauka(): ?string
    {
        return $this->nuotrauka;
    }

    public function setNuotrauka(?string $nuotrauka): self
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


}

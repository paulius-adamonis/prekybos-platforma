<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TurgausPreke
 *
 * @ORM\Table(name="TURGAUS_PREKE", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})}, indexes={@ORM\Index(name="priklauso19", columns={"fk_turg_prekes_kategorija"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\TurgausPrekeRepository")
 */
class TurgausPreke
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
     * @ORM\Column(name="pavadinimas", type="string", length=100, nullable=false)
     */
    private $pavadinimas;

    /**
     * @var string
     *
     * @ORM\Column(name="aprasymas", type="string", length=500, nullable=false)
     */
    private $aprasymas;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nuotrauka", type="string", length=100, nullable=true)
     */
    private $nuotrauka;

    /**
     * @var float|null
     *
     * @ORM\Column(name="pradine_kaina", type="float", precision=10, scale=0, nullable=true)
     */
    private $pradineKaina;

    /**
     * @var float|null
     *
     * @ORM\Column(name="kaina", type="float", precision=10, scale=0, nullable=true)
     */
    private $kaina;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="pabaigos_data", type="date", nullable=true)
     */
    private $pabaigosData;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data", type="date", nullable=false)
     */
    private $data;

    /**
     * @var float|null
     *
     * @ORM\Column(name="minimalus_statymas", type="float", precision=10, scale=0, nullable=true)
     */
    private $minimalusStatymas;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ieskomos_prekes_aprasymas", type="string", length=500, nullable=true)
     */
    private $ieskomosPrekesAprasymas;

    /**
     * @var \TurgPrekesKategorija
     *
     * @ORM\ManyToOne(targetEntity="TurgPrekesKategorija")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_turg_prekes_kategorija", referencedColumnName="id")
     * })
     */
    private $fkTurgPrekesKategorija;

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

    public function getNuotrauka(): ?string
    {
        return $this->nuotrauka;
    }

    public function setNuotrauka(?string $nuotrauka): self
    {
        $this->nuotrauka = $nuotrauka;

        return $this;
    }

    public function getPradineKaina(): ?float
    {
        return $this->pradineKaina;
    }

    public function setPradineKaina(?float $pradineKaina): self
    {
        $this->pradineKaina = $pradineKaina;

        return $this;
    }

    public function getKaina(): ?float
    {
        return $this->kaina;
    }

    public function setKaina(?float $kaina): self
    {
        $this->kaina = $kaina;

        return $this;
    }

    public function getPabaigosData(): ?\DateTimeInterface
    {
        return $this->pabaigosData;
    }

    public function setPabaigosData(?\DateTimeInterface $pabaigosData): self
    {
        $this->pabaigosData = $pabaigosData;

        return $this;
    }

    public function getData(): ?\DateTimeInterface
    {
        return $this->data;
    }

    public function setData(\DateTimeInterface $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getMinimalusStatymas(): ?float
    {
        return $this->minimalusStatymas;
    }

    public function setMinimalusStatymas(?float $minimalusStatymas): self
    {
        $this->minimalusStatymas = $minimalusStatymas;

        return $this;
    }

    public function getIeskomosPrekesAprasymas(): ?string
    {
        return $this->ieskomosPrekesAprasymas;
    }

    public function setIeskomosPrekesAprasymas(?string $ieskomosPrekesAprasymas): self
    {
        $this->ieskomosPrekesAprasymas = $ieskomosPrekesAprasymas;

        return $this;
    }

    public function getFkTurgPrekesKategorija(): ?TurgPrekesKategorija
    {
        return $this->fkTurgPrekesKategorija;
    }

    public function setFkTurgPrekesKategorija(?TurgPrekesKategorija $fkTurgPrekesKategorija): self
    {
        $this->fkTurgPrekesKategorija = $fkTurgPrekesKategorija;

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

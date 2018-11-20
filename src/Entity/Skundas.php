<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Skundas
 *
 * @ORM\Table(name="SKUNDAS", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})}, indexes={@ORM\Index(name="priklauso15", columns={"fk_skundo_tipas"}), @ORM\Index(name="priklauso2", columns={"fk_busena"}), @ORM\Index(name="pareiske", columns={"fk_pareiskejas"}), @ORM\Index(name="gavo", columns={"fk_gavejas"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\SkundasRepository")
 */
class Skundas
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
     * @var \DateTime
     *
     * @ORM\Column(name="data", type="date", nullable=false)
     */
    private $data;

    /**
     * @var string
     *
     * @ORM\Column(name="skundas", type="string", length=255, nullable=false)
     */
    private $skundas;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="tikrinimo_data", type="date", nullable=true)
     */
    private $tikrinimoData;

    /**
     * @var \Vartotojas
     *
     * @ORM\ManyToOne(targetEntity="Vartotojas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_gavejas", referencedColumnName="id")
     * })
     */
    private $fkGavejas;

    /**
     * @var \Vartotojas
     *
     * @ORM\ManyToOne(targetEntity="Vartotojas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_pareiskejas", referencedColumnName="id")
     * })
     */
    private $fkPareiskejas;

    /**
     * @var \SkundoTipas
     *
     * @ORM\ManyToOne(targetEntity="SkundoTipas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_skundo_tipas", referencedColumnName="id")
     * })
     */
    private $fkSkundoTipas;

    /**
     * @var \Busena
     *
     * @ORM\ManyToOne(targetEntity="Busena")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_busena", referencedColumnName="id")
     * })
     */
    private $fkBusena;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSkundas(): ?string
    {
        return $this->skundas;
    }

    public function setSkundas(string $skundas): self
    {
        $this->skundas = $skundas;

        return $this;
    }

    public function getTikrinimoData(): ?\DateTimeInterface
    {
        return $this->tikrinimoData;
    }

    public function setTikrinimoData(?\DateTimeInterface $tikrinimoData): self
    {
        $this->tikrinimoData = $tikrinimoData;

        return $this;
    }

    public function getFkGavejas(): ?Vartotojas
    {
        return $this->fkGavejas;
    }

    public function setFkGavejas(?Vartotojas $fkGavejas): self
    {
        $this->fkGavejas = $fkGavejas;

        return $this;
    }

    public function getFkPareiskejas(): ?Vartotojas
    {
        return $this->fkPareiskejas;
    }

    public function setFkPareiskejas(?Vartotojas $fkPareiskejas): self
    {
        $this->fkPareiskejas = $fkPareiskejas;

        return $this;
    }

    public function getFkSkundoTipas(): ?SkundoTipas
    {
        return $this->fkSkundoTipas;
    }

    public function setFkSkundoTipas(?SkundoTipas $fkSkundoTipas): self
    {
        $this->fkSkundoTipas = $fkSkundoTipas;

        return $this;
    }

    public function getFkBusena(): ?Busena
    {
        return $this->fkBusena;
    }

    public function setFkBusena(?Busena $fkBusena): self
    {
        $this->fkBusena = $fkBusena;

        return $this;
    }


}

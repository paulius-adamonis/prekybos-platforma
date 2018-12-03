<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Klausimas
 *
 * @ORM\Table(name="KLAUSIMAS", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})}, indexes={@ORM\Index(name="atsako", columns={"fk_atsakytojas"}), @ORM\Index(name="uzduoda", columns={"fk_klausiantysis"}), @ORM\Index(name="priklauso4", columns={"fk_klausimo_tipas"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\KlausimasRepository")
 */
class Klausimas
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
     * @ORM\Column(name="parasymo_data", type="date", nullable=false)
     */
    private $parasymoData;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="atsakymo_data", type="date", nullable=true)
     */
    private $atsakymoData;

    /**
     * @var string
     *
     * @ORM\Column(name="klausimas", type="string", length=255, nullable=false)
     */
    private $klausimas;

    /**
     * @var \Vartotojas
     *
     * @ORM\ManyToOne(targetEntity="Vartotojas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_atsakytojas", referencedColumnName="id", nullable=true)
     * })
     */
    private $fkAtsakytojas;

    /**
     * @var \KlausimoTipas
     *
     * @ORM\ManyToOne(targetEntity="KlausimoTipas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_klausimo_tipas", referencedColumnName="id", nullable=false)
     * })
     */
    private $fkKlausimoTipas;

    /**
     * @var \Vartotojas
     *
     * @ORM\ManyToOne(targetEntity="Vartotojas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_klausiantysis", referencedColumnName="id", nullable=false)
     * })
     */
    private $fkKlausiantysis;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParasymoData(): ?\DateTimeInterface
    {
        return $this->parasymoData;
    }

    public function setParasymoData(\DateTimeInterface $parasymoData): self
    {
        $this->parasymoData = $parasymoData;

        return $this;
    }

    public function getAtsakymoData(): ?\DateTimeInterface
    {
        return $this->atsakymoData;
    }

    public function setAtsakymoData(?\DateTimeInterface $atsakymoData): self
    {
        $this->atsakymoData = $atsakymoData;

        return $this;
    }

    public function getKlausimas(): ?string
    {
        return $this->klausimas;
    }

    public function setKlausimas(string $klausimas): self
    {
        $this->klausimas = $klausimas;

        return $this;
    }

    public function getFkAtsakytojas(): ?Vartotojas
    {
        return $this->fkAtsakytojas;
    }

    public function setFkAtsakytojas(?Vartotojas $fkAtsakytojas): self
    {
        $this->fkAtsakytojas = $fkAtsakytojas;

        return $this;
    }

    public function getFkKlausimoTipas(): ?KlausimoTipas
    {
        return $this->fkKlausimoTipas;
    }

    public function setFkKlausimoTipas(?KlausimoTipas $fkKlausimoTipas): self
    {
        $this->fkKlausimoTipas = $fkKlausimoTipas;

        return $this;
    }

    public function getFkKlausiantysis(): ?Vartotojas
    {
        return $this->fkKlausiantysis;
    }

    public function setFkKlausiantysis(?Vartotojas $fkKlausiantysis): self
    {
        $this->fkKlausiantysis = $fkKlausiantysis;

        return $this;
    }


}

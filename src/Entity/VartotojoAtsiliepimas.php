<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VartotojoAtsiliepimas
 *
 * @ORM\Table(name="VARTOTOJO_ATSILIEPIMAS", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})}, indexes={@ORM\Index(name="gauna1", columns={"fk_gavejas"}), @ORM\Index(name="raso3", columns={"fk_rasytojas"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\VartotojoAtsiliepimasRepository")
 */
class VartotojoAtsiliepimas
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
     * @var float
     *
     * @ORM\Column(name="reitingas", type="float", precision=10, scale=0, nullable=false)
     */
    private $reitingas;

    /**
     * @var string
     *
     * @ORM\Column(name="aprasymas", type="string", length=255, nullable=false)
     */
    private $aprasymas;

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
     *   @ORM\JoinColumn(name="fk_rasytojas", referencedColumnName="id")
     * })
     */
    private $fkRasytojas;

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

    public function getReitingas(): ?float
    {
        return $this->reitingas;
    }

    public function setReitingas(float $reitingas): self
    {
        $this->reitingas = $reitingas;

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

    public function getFkGavejas(): ?Vartotojas
    {
        return $this->fkGavejas;
    }

    public function setFkGavejas(?Vartotojas $fkGavejas): self
    {
        $this->fkGavejas = $fkGavejas;

        return $this;
    }

    public function getFkRasytojas(): ?Vartotojas
    {
        return $this->fkRasytojas;
    }

    public function setFkRasytojas(?Vartotojas $fkRasytojas): self
    {
        $this->fkRasytojas = $fkRasytojas;

        return $this;
    }


}

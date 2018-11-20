<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Atsiliepimas
 *
 * @ORM\Table(name="ATSILIEPIMAS", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})}, indexes={@ORM\Index(name="priklauso1", columns={"fk_turgaus_preke"}), @ORM\Index(name="priklauso", columns={"fk_parduotuves_preke"}), @ORM\Index(name="raso", columns={"fk_vartotojas"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\AtsiliepimasRepository")
 */
class Atsiliepimas
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
     * @ORM\Column(name="tekstas", type="string", length=500, nullable=false)
     */
    private $tekstas;

    /**
     * @var float
     *
     * @ORM\Column(name="reitingas", type="float", precision=10, scale=0, nullable=false)
     */
    private $reitingas;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data", type="date", nullable=false)
     */
    private $data;

    /**
     * @var \ParduotuvesPreke
     *
     * @ORM\ManyToOne(targetEntity="ParduotuvesPreke")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_parduotuves_preke", referencedColumnName="id")
     * })
     */
    private $fkParduotuvesPreke;

    /**
     * @var \TurgausPreke
     *
     * @ORM\ManyToOne(targetEntity="TurgausPreke")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_turgaus_preke", referencedColumnName="id")
     * })
     */
    private $fkTurgausPreke;

    /**
     * @var \Vartotojas
     *
     * @ORM\ManyToOne(targetEntity="Vartotojas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_vartotojas", referencedColumnName="id")
     * })
     */
    private $fkVartotojas;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTekstas(): ?string
    {
        return $this->tekstas;
    }

    public function setTekstas(string $tekstas): self
    {
        $this->tekstas = $tekstas;

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

    public function getData(): ?\DateTimeInterface
    {
        return $this->data;
    }

    public function setData(\DateTimeInterface $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getFkParduotuvesPreke(): ?ParduotuvesPreke
    {
        return $this->fkParduotuvesPreke;
    }

    public function setFkParduotuvesPreke(?ParduotuvesPreke $fkParduotuvesPreke): self
    {
        $this->fkParduotuvesPreke = $fkParduotuvesPreke;

        return $this;
    }

    public function getFkTurgausPreke(): ?TurgausPreke
    {
        return $this->fkTurgausPreke;
    }

    public function setFkTurgausPreke(?TurgausPreke $fkTurgausPreke): self
    {
        $this->fkTurgausPreke = $fkTurgausPreke;

        return $this;
    }

    public function getFkVartotojas(): ?Vartotojas
    {
        return $this->fkVartotojas;
    }

    public function setFkVartotojas(?Vartotojas $fkVartotojas): self
    {
        $this->fkVartotojas = $fkVartotojas;

        return $this;
    }


}

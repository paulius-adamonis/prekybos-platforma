<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PardPardavimas
 *
 * @ORM\Table(name="PARD_PARDAVIMAS", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})}, indexes={@ORM\Index(name="priklauso8", columns={"fk_parduotuves_preke"}), @ORM\Index(name="perka", columns={"fk_vartotojas"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\PardPardavimasRepository")
 */
class PardPardavimas
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
     * @var int
     *
     * @ORM\Column(name="kiekis", type="integer", nullable=false)
     */
    private $kiekis;

    /**
     * @var \Vartotojas
     *
     * @ORM\ManyToOne(targetEntity="Vartotojas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_vartotojas", referencedColumnName="id")
     * })
     */
    private $fkVartotojas;

    /**
     * @var \ParduotuvesPreke
     *
     * @ORM\ManyToOne(targetEntity="ParduotuvesPreke")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_parduotuves_preke", referencedColumnName="id")
     * })
     */
    private $fkParduotuvesPreke;

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

    public function getKiekis(): ?int
    {
        return $this->kiekis;
    }

    public function setKiekis(int $kiekis): self
    {
        $this->kiekis = $kiekis;

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

    public function getFkParduotuvesPreke(): ?ParduotuvesPreke
    {
        return $this->fkParduotuvesPreke;
    }

    public function setFkParduotuvesPreke(?ParduotuvesPreke $fkParduotuvesPreke): self
    {
        $this->fkParduotuvesPreke = $fkParduotuvesPreke;

        return $this;
    }


}

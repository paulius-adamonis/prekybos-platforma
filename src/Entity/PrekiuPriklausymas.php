<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PrekiuPriklausymas
 *
 * @ORM\Table(name="PREKIU_PRIKLAUSYMAS", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})}, indexes={@ORM\Index(name="turi2", columns={"fk_sandelis"}), @ORM\Index(name="priklauso5", columns={"fk_kokybe"}), @ORM\Index(name="priklauso11", columns={"fk_parduotuves_preke"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\PrekiuPriklausymasRepository")
 */
class PrekiuPriklausymas
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
     * @var int
     *
     * @ORM\Column(name="kiekis", type="integer", nullable=false)
     */
    private $kiekis;

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
     * @var \Kokybe
     *
     * @ORM\ManyToOne(targetEntity="Kokybe")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_kokybe", referencedColumnName="id")
     * })
     */
    private $fkKokybe;

    /**
     * @var \Sandelis
     *
     * @ORM\ManyToOne(targetEntity="Sandelis")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_sandelis", referencedColumnName="id")
     * })
     */
    private $fkSandelis;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFkParduotuvesPreke(): ?ParduotuvesPreke
    {
        return $this->fkParduotuvesPreke;
    }

    public function setFkParduotuvesPreke(?ParduotuvesPreke $fkParduotuvesPreke): self
    {
        $this->fkParduotuvesPreke = $fkParduotuvesPreke;

        return $this;
    }

    public function getFkKokybe(): ?Kokybe
    {
        return $this->fkKokybe;
    }

    public function setFkKokybe(?Kokybe $fkKokybe): self
    {
        $this->fkKokybe = $fkKokybe;

        return $this;
    }

    public function getFkSandelis(): ?Sandelis
    {
        return $this->fkSandelis;
    }

    public function setFkSandelis(?Sandelis $fkSandelis): self
    {
        $this->fkSandelis = $fkSandelis;

        return $this;
    }


}

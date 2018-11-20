<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PardPrekiuKategorijuPriklausymas
 *
 * @ORM\Table(name="PARD_PREKIU_KATEGORIJU_PRIKLAUSYMAS", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})}, indexes={@ORM\Index(name="priklauso9", columns={"fk_parduotuves_prekes_kategorija"}), @ORM\Index(name="turi1", columns={"fk_parduotuves_preke"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\PardPrekiuKategorijuPriklausymasRepository")
 */
class PardPrekiuKategorijuPriklausymas
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
     * @var \ParduotuvesPrekesKategorija
     *
     * @ORM\ManyToOne(targetEntity="ParduotuvesPrekesKategorija")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_parduotuves_prekes_kategorija", referencedColumnName="id")
     * })
     */
    private $fkParduotuvesPrekesKategorija;

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

    public function getFkParduotuvesPrekesKategorija(): ?ParduotuvesPrekesKategorija
    {
        return $this->fkParduotuvesPrekesKategorija;
    }

    public function setFkParduotuvesPrekesKategorija(?ParduotuvesPrekesKategorija $fkParduotuvesPrekesKategorija): self
    {
        $this->fkParduotuvesPrekesKategorija = $fkParduotuvesPrekesKategorija;

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

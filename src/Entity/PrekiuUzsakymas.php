<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PrekiuUzsakymas
 *
 * @ORM\Table(name="PREKIU_UZSAKYMAS", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})}, indexes={@ORM\Index(name="uzsako", columns={"fk_vartotojas"}), @ORM\Index(name="priklauso13", columns={"fk_sandelis"}), @ORM\Index(name="uzsakoma", columns={"fk_parduotuves_preke"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\PrekiuUzsakymasRepository")
 */
class PrekiuUzsakymas
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
     * @Assert\NotBlank
     *
     * @ORM\Column(name="kiekis", type="integer", nullable=false)
     */
    private $kiekis;

    /**
     * @var \Sandelis
     *
     * @ORM\ManyToOne(targetEntity="Sandelis")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_sandelis", referencedColumnName="id")
     * })
     */
    private $fkSandelis;

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

    public function getKiekis(): ?int
    {
        return $this->kiekis;
    }

    public function setKiekis(int $kiekis): self
    {
        $this->kiekis = $kiekis;

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

<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PrekiuNarsymoIstorija
 *
 * @ORM\Table(name="PREKIU_NARSYMO_ISTORIJA", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})}, indexes={@ORM\Index(name="ieina_i2", columns={"fk_vartotojas"}), @ORM\Index(name="ieina_i", columns={"fk_parduotuves_preke"}), @ORM\Index(name="ieina_i1", columns={"fk_turgaus_preke"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\PrekiuNarsymoIstorijaRepository")
 */
class PrekiuNarsymoIstorija
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
     * @ORM\Column(name="skaitliukas", type="integer", nullable=false)
     */
    private $skaitliukas = '0';

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

    public function getSkaitliukas(): ?int
    {
        return $this->skaitliukas;
    }

    public function setSkaitliukas(int $skaitliukas): self
    {
        $this->skaitliukas = $skaitliukas;

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

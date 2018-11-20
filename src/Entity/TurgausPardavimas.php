<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TurgausPardavimas
 *
 * @ORM\Table(name="TURGAUS_PARDAVIMAS", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})}, indexes={@ORM\Index(name="parduoda", columns={"fk_pardavejas"}), @ORM\Index(name="perka1", columns={"fk_pirkejas"}), @ORM\Index(name="priklauso20", columns={"fk_turgaus_preke"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\TurgausPardavimasRepository")
 */
class TurgausPardavimas
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
     *   @ORM\JoinColumn(name="fk_pardavejas", referencedColumnName="id")
     * })
     */
    private $fkPardavejas;

    /**
     * @var \Vartotojas
     *
     * @ORM\ManyToOne(targetEntity="Vartotojas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_pirkejas", referencedColumnName="id")
     * })
     */
    private $fkPirkejas;

    /**
     * @var \TurgausPreke
     *
     * @ORM\ManyToOne(targetEntity="TurgausPreke")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_turgaus_preke", referencedColumnName="id")
     * })
     */
    private $fkTurgausPreke;

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

    public function getFkPardavejas(): ?Vartotojas
    {
        return $this->fkPardavejas;
    }

    public function setFkPardavejas(?Vartotojas $fkPardavejas): self
    {
        $this->fkPardavejas = $fkPardavejas;

        return $this;
    }

    public function getFkPirkejas(): ?Vartotojas
    {
        return $this->fkPirkejas;
    }

    public function setFkPirkejas(?Vartotojas $fkPirkejas): self
    {
        $this->fkPirkejas = $fkPirkejas;

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


}

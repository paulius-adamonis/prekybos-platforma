<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Islaidos
 *
 * @ORM\Table(name="ISLAIDOS", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})}, indexes={@ORM\Index(name="priklauso3", columns={"fk_islaidos_tipas"}), @ORM\Index(name="turi", columns={"fk_sandelis"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\IslaidosRepository")
 */
class Islaidos
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
     * @ORM\Column(name="kaina", type="float", precision=10, scale=0, nullable=false)
     */
    private $kaina;

    /**
     * @var \IslaidosTipas
     *
     * @ORM\ManyToOne(targetEntity="IslaidosTipas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_islaidos_tipas", referencedColumnName="id")
     * })
     */
    private $fkIslaidosTipas;

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

    public function getData(): ?\DateTimeInterface
    {
        return $this->data;
    }

    public function setData(\DateTimeInterface $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getKaina(): ?float
    {
        return $this->kaina;
    }

    public function setKaina(float $kaina): self
    {
        $this->kaina = $kaina;

        return $this;
    }

    public function getFkIslaidosTipas(): ?IslaidosTipas
    {
        return $this->fkIslaidosTipas;
    }

    public function setFkIslaidosTipas(?IslaidosTipas $fkIslaidosTipas): self
    {
        $this->fkIslaidosTipas = $fkIslaidosTipas;

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

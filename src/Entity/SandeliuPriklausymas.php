<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SandeliuPriklausymas
 *
 * @ORM\Table(name="SANDELIU_PRIKLAUSYMAS", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})}, indexes={@ORM\Index(name="priklauso14", columns={"fk_sandelis"}), @ORM\Index(name="turi3", columns={"fk_vartotojas"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\SandeliuPriklausymasRepository")
 */
class SandeliuPriklausymas
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

    public function getId(): ?int
    {
        return $this->id;
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


}

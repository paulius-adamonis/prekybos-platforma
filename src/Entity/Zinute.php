<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zinute
 *
 * @ORM\Table(name="ZINUTE", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})}, indexes={@ORM\Index(name="raso2", columns={"fk_rasytojas"}), @ORM\Index(name="gauna", columns={"fk_gavejas"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\ZinuteRepository")
 */
class Zinute
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
     * @var \DateTime|null
     *
     * @ORM\Column(name="perziuros_data", type="date", nullable=true)
     */
    private $perziurosData;

    /**
     * @var string
     *
     * @ORM\Column(name="zinute", type="string", length=255, nullable=false)
     */
    private $zinute;

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

    public function getPerziurosData(): ?\DateTimeInterface
    {
        return $this->perziurosData;
    }

    public function setPerziurosData(?\DateTimeInterface $perziurosData): self
    {
        $this->perziurosData = $perziurosData;

        return $this;
    }

    public function getZinute(): ?string
    {
        return $this->zinute;
    }

    public function setZinute(string $zinute): self
    {
        $this->zinute = $zinute;

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

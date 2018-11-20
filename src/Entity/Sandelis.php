<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sandelis
 *
 * @ORM\Table(name="SANDELIS", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\SandelisRepository")
 */
class Sandelis
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
     * @ORM\Column(name="adresas", type="string", length=50, nullable=false)
     */
    private $adresas;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdresas(): ?string
    {
        return $this->adresas;
    }

    public function setAdresas(string $adresas): self
    {
        $this->adresas = $adresas;

        return $this;
    }


}

<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Busena
 *
 * @ORM\Table(name="BUSENA", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\BusenaRepository")
 */
class Busena
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
     * @ORM\Column(name="pavadinimas", type="string", length=20, nullable=false)
     */
    private $pavadinimas;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPavadinimas(): ?string
    {
        return $this->pavadinimas;
    }

    /**
     * @param string|null $pavadinimas
     * @return $this
     */
    public function setPavadinimas(?string $pavadinimas): self
    {
        $this->pavadinimas = $pavadinimas;

        return $this;
    }


}

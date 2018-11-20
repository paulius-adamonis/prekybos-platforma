<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IslaidosTipas
 *
 * @ORM\Table(name="ISLAIDOS_TIPAS", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\IslaidosTipasRepository")
 */
class IslaidosTipas
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
     * @ORM\Column(name="pavadinimas", type="string", length=50, nullable=false)
     */
    private $pavadinimas;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPavadinimas(): ?string
    {
        return $this->pavadinimas;
    }

    public function setPavadinimas(string $pavadinimas): self
    {
        $this->pavadinimas = $pavadinimas;

        return $this;
    }


}

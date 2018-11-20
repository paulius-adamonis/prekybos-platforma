<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * KlausimoTipas
 *
 * @ORM\Table(name="KLAUSIMO_TIPAS", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\KlausimoTipasRepository")
 */
class KlausimoTipas
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
     * @ORM\Column(name="pavadinimas", type="string", length=255, nullable=false)
     */
    private $pavadinimas;

    /**
     * @var string
     *
     * @ORM\Column(name="aprasas", type="string", length=255, nullable=false)
     */
    private $aprasas;

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

    public function getAprasas(): ?string
    {
        return $this->aprasas;
    }

    public function setAprasas(string $aprasas): self
    {
        $this->aprasas = $aprasas;

        return $this;
    }


}

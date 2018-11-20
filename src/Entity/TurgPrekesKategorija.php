<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TurgPrekesKategorija
 *
 * @ORM\Table(name="TURG_PREKES_KATEGORIJA", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})}, indexes={@ORM\Index(name="kuria", columns={"fk_vartotojas"}), @ORM\Index(name="priklauso10", columns={"fk_pardavimo_tipas"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\TurgPrekesKategorijaRepository")
 */
class TurgPrekesKategorija
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
     * @ORM\Column(name="pavadinimas", type="string", length=100, nullable=false)
     */
    private $pavadinimas;

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
     * @var \PardavimoTipas
     *
     * @ORM\ManyToOne(targetEntity="PardavimoTipas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_pardavimo_tipas", referencedColumnName="id")
     * })
     */
    private $fkPardavimoTipas;

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

    public function getFkVartotojas(): ?Vartotojas
    {
        return $this->fkVartotojas;
    }

    public function setFkVartotojas(?Vartotojas $fkVartotojas): self
    {
        $this->fkVartotojas = $fkVartotojas;

        return $this;
    }

    public function getFkPardavimoTipas(): ?PardavimoTipas
    {
        return $this->fkPardavimoTipas;
    }

    public function setFkPardavimoTipas(?PardavimoTipas $fkPardavimoTipas): self
    {
        $this->fkPardavimoTipas = $fkPardavimoTipas;

        return $this;
    }


}

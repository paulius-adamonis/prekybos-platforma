<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Vartotojas
 *
 * @ORM\Table(name="VARTOTOJAS", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})})
 * @ORM\Entity(repositoryClass="App\Repository\VartotojasRepository")
 */
class Vartotojas implements UserInterface
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
     * @ORM\Column(name="vardas", type="string", length=25, nullable=false)
     */
    private $vardas;

    /**
     * @var string
     *
     * @ORM\Column(name="pavarde", type="string", length=25, nullable=false)
     */
    private $pavarde;

    /**
     * @var string
     *
     * @ORM\Column(name="el_pastas", type="string", length=75, nullable=false)
     */
    private $elPastas;

    /**
     * @var string The hashed password
     * @ORM\Column(name="slaptazodis", type="string", length=255, nullable=false)
     */
    private $slaptazodis;

    /**
     * @Assert\NotBlank
     */
    private $plainPassword;

    /**
     * @var int
     *
     * @ORM\Column(name="tel_nr", type="integer", nullable=false)
     */
    private $telNr;

    /**
     * @ORM\Column(name="role", type="json", nullable=false)
     */
    private $roles = [];

    /**
     * @var bool
     *
     * @ORM\Column(name="ar_aktyvus", type="boolean", nullable=false, options={"default"="1"})
     */
    private $arAktyvus = '1';

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Skundas", mappedBy="fkPareiskejas")
     */
    private $parasytiSkundai;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Skundas", mappedBy="fkGavejas")
     */
    private $gautiSkundai;


    public function __construct()
    {
        $this->parasytiSkundai = new ArrayCollection();
        $this->gautiSkundai = new ArrayCollection();
    }

    /**
     * @return Collection|Skundas[]
     */
    public function getParasytusSkundus(): Collection{
        return $this->parasytiSkundai;
    }

    public function addParasytaSkunda(Skundas $skundas){
        $this->parasytiSkundai->add($skundas);
    }

    /**
     * @return Collection|Skundas[]
     */
    public function getGautusSkundus(): Collection{
        return $this->gautiSkundai;
    }

    public function addGautaSkunda(Skundas $skundas){
        $this->gautiSkundai->add($skundas);
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->elPastas;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRole($roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function setRoles($roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getRole()
    {
        return $this->roles;
    }

    public function addRole($role){
        $this->roles[] = $role;
    }

    public function removeRoles($roles){
        $this->roles = array_diff($this->roles, $roles);
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->slaptazodis;
    }

    public function setPassword(string $password): self
    {
        $this->slaptazodis = $password;

        return $this;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVardas(): ?string
    {
        return $this->vardas;
    }

    public function setVardas(string $vardas): self
    {
        $this->vardas = $vardas;

        return $this;
    }

    public function getPavarde(): ?string
    {
        return $this->pavarde;
    }

    public function setPavarde(string $pavarde): self
    {
        $this->pavarde = $pavarde;

        return $this;
    }

    public function getElPastas(): ?string
    {
        return $this->elPastas;
    }

    public function setElPastas(string $elPastas): self
    {
        $this->elPastas = $elPastas;

        return $this;
    }

    public function getTelNr(): ?int
    {
        return $this->telNr;
    }

    public function setTelNr(int $telNr): self
    {
        $this->telNr = $telNr;

        return $this;
    }

    public function getArAktyvus(): ?bool
    {
        return $this->arAktyvus;
    }

    public function setArAktyvus(bool $arAktyvus): self
    {
        $this->arAktyvus = $arAktyvus;

        return $this;
    }
}

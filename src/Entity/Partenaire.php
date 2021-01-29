<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;


/**
 *@ApiResource(normalizationContext={"groups"={"partenaire"}})
 * @ORM\Entity(repositoryClass="App\Repository\PartenaireRepository")
 *@ApiFilter(SearchFilter::class, properties={"ninea": "exact"})
 */
class Partenaire 
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @Groups({"comptes"})
     * @Groups({"user"})
     * @ORM\Column(type="integer")
     * @Groups({"partenaire"})
     * @Groups({"transaction"})
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Groups({"user"})
     * @Groups({"comptes"})
     * @Groups({"partenaire"})
     * @Assert\NotBlank(message="ce champ est obligatoire")
     */
    private $ninea;

    /**
     * @ORM\Column(type="text")
     * @Groups({"user"})
     * @Groups({"comptes"})
     * @Groups({"partenaire"})
     * @Assert\NotBlank(message="ce champ est obligatoire")
     */
    private $register;

   

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Compte", mappedBy="partenaire",cascade={"remove"})
     * @Groups({"partenaire"})
     */
    private $comptes;

  
    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Contrat", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     *  @Groups({"partenaire"})
     */
    private $contrats;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="partenaire",cascade={"remove"})
     *  @Groups({"partenaire"})
     * @Groups({"comptes"})
     */
    private $users;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"partenaire"})
     * @Assert\NotBlank(message="ce champ est obligatoire")
     */
    private $adresse;

    /**
     * @ORM\Column(type="integer", length=255)
     * @Groups({"partenaire"})
     * @Assert\NotBlank(message="ce champ est obligatoire")
     * @Assert\Length(min= 9,minMessage="ce champ est obligatoire")
     * @Assert\Regex(pattern="/^[0-9]*$/",message="chifres uniquement")
     */
    private $tel;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user"})
     * @Groups({"comptes"})
     * @Groups({"partenaire"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user"})
     * @Groups({"partenaire"})
     */
    private $localite;



  

  

   
    public function __construct()
    {
        $this->comptes = new ArrayCollection();
        $this->users = new ArrayCollection();
       
    }

    public function getId(): ?int
    {
        return $this->id;



        
    }

    public function getNinea(): ?string
    {
        return $this->ninea;
    }

    public function setNinea(string $ninea): self
    {
        $this->ninea = $ninea;

        return $this;
    }

    public function getRegister(): ?string
    {
        return $this->register;
    }

    public function setRegister(string $register): self
    {
        $this->register = $register;

        return $this;
    }

    

    /**
     * @return Collection|Compte[]
     */
    public function getComptes(): Collection
    {
        return $this->comptes;
    }

    public function addCompte(Compte $compte): self
    {
        if (!$this->comptes->contains($compte)) {
            $this->comptes[] = $compte;
            $compte->setPartenaire($this);
        }

        return $this;
    }

    public function removeCompte(Compte $compte): self
    {
        if ($this->comptes->contains($compte)) {
            $this->comptes->removeElement($compte);
            // set the owning side to null (unless already changed)
            if ($compte->getPartenaire() === $this) {
                $compte->setPartenaire(null);
            }
        }

        return $this;
    }


    public function getContrats(): ?Contrat
    {
        return $this->contrats;
    }

    public function setContrats(Contrat $contrats): self
    {
        $this->contrats = $contrats;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setPartenaire($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getPartenaire() === $this) {
                $user->setPartenaire(null);
            }
        }

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLocalite(): ?string
    {
        return $this->localite;
    }

    public function setLocalite(string $localite): self
    {
        $this->localite = $localite;

        return $this;
    }

}

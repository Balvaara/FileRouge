<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\GeneratedValue;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 *@ApiResource(normalizationContext={"groups"={"user"}}))
 *@ApiFilter(SearchFilter::class, properties={"id": "exact"})
 */
class User implements AdvancedUserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"user"})
     * @Groups({"transaction"})
     *  @Groups({"partenaire"})
     * @Groups({"affectation"})
     * @Groups({"depot"})
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=180, unique=true, nullable=false)
     * @Groups({"user"})
     * @Groups({"depot"})
     * @Groups({"partenaire"})
     * @Groups({"affectation"})
     *@Groups({"transaction"})
     * @Assert\NotBlank(message="ce champ est obligatoire")
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     * @Groups({"user"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"user"})
     * 
     */
    private $password;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Role", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user"})
     */
    private $profil;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user"})
     * @Groups({"comptes"})
     * @Groups({"depot"})
     * @Groups({"affectation"})
     * @Groups({"partenaire"})
     * @Groups({"transaction"})
     */
    private $nomComplet;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"user"})
     * @Groups({"partenaire"})
     */
    private $isActive;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Compte", mappedBy="user")
     * @Groups({"user"})
     */
    private $comptes;

   
   
   

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Affectaion", mappedBy="userCon")
     * @Groups({"user"})
     */
    private $compteAffecte;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction", mappedBy="retaitUse")
     *  @Groups({"user"})
     */
    private $transactions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction", mappedBy="depotUse")
     */
    private $depTranUse;

   

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Depot", mappedBy="user")
     */
    private $depots;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Partenaire", inversedBy="users")
     * @Groups({"user"})
     * @Groups({"transaction"})
     */
    private $partenaire;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $photo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Affectaion", mappedBy="users",cascade={"remove"})
     * @Groups({"user"})
     */
    private $affectations;


  
  


   
    

    public function __construct()
    {
        $this->comptes = new ArrayCollection();
        $this->updatedAt = new \DateTime();
        $this->compteAffecte = new ArrayCollection();
        $this->transactions = new ArrayCollection();
        $this->depTranUse = new ArrayCollection();
        $this->depots = new ArrayCollection();
        $this->affectations = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
      

        return $roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
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
        // $this->plainPassword = null;
    }

    public function getProfil(): ?Role
    {
        return $this->profil;
    }

    public function setProfil(?Role $profil): self
    {
        $this->profil = $profil;

        return $this;
    }

    public function getNomComplet(): ?string
    {
        return $this->nomComplet;
    }

    public function setNomComplet(string $nomComplet): self
    {
        $this->nomComplet = $nomComplet;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }
    public function isAccountNonExpired(){
        return true;
    }
    public function isAccountNonLocked(){
        return true;
    }
    public function isCredentialsNonExpired()
    {
        return true;
    }
    public function isEnabled(){
        return $this->isActive;
    }

    

   

   
 


    /**
     * @return Collection|Affectaion[]
     */
    public function getCompteAffecte(): Collection
    {
        return $this->compteAffecte;
    }

    public function addCompteAffecte(Affectaion $compteAffecte): self
    {
        if (!$this->compteAffecte->contains($compteAffecte)) {
            $this->compteAffecte[] = $compteAffecte;
            $compteAffecte->setUserCon($this);
        }

        return $this;
    }

    public function removeCompteAffecte(Affectaion $compteAffecte): self
    {
        if ($this->compteAffecte->contains($compteAffecte)) {
            $this->compteAffecte->removeElement($compteAffecte);
            // set the owning side to null (unless already changed)
            if ($compteAffecte->getUserCon() === $this) {
                $compteAffecte->setUserCon(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->setRetaitUse($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->contains($transaction)) {
            $this->transactions->removeElement($transaction);
            // set the owning side to null (unless already changed)
            if ($transaction->getRetaitUse() === $this) {
                $transaction->setRetaitUse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getDepTranUse(): Collection
    {
        return $this->depTranUse;
    }

    public function addDepTranUse(Transaction $depTranUse): self
    {
        if (!$this->depTranUse->contains($depTranUse)) {
            $this->depTranUse[] = $depTranUse;
            $depTranUse->setDepotUse($this);
        }

        return $this;
    }

    public function removeDepTranUse(Transaction $depTranUse): self
    {
        if ($this->depTranUse->contains($depTranUse)) {
            $this->depTranUse->removeElement($depTranUse);
            // set the owning side to null (unless already changed)
            if ($depTranUse->getDepotUse() === $this) {
                $depTranUse->setDepotUse(null);
            }
        }

        return $this;
    }

    

    /**
     * @return Collection|Depot[]
     */
    public function getDepots(): Collection
    {
        return $this->depots;
    }

    public function addDepot(Depot $depot): self
    {
        if (!$this->depots->contains($depot)) {
            $this->depots[] = $depot;
            $depot->setUser($this);
        }

        return $this;
    }

    public function removeDepot(Depot $depot): self
    {
        if ($this->depots->contains($depot)) {
            $this->depots->removeElement($depot);
            // set the owning side to null (unless already changed)
            if ($depot->getUser() === $this) {
                $depot->setUser(null);
            }
        }

        return $this;
    }

    public function getPartenaire(): ?Partenaire
    {
        return $this->partenaire;
    }

    public function setPartenaire(?Partenaire $partenaire): self
    {
        $this->partenaire = $partenaire;

        return $this;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto($photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return Collection|Affectaion[]
     */
    public function getAffectations(): Collection
    {
        return $this->affectations;
    }

    public function addAffectation(Affectaion $affectation): self
    {
        if (!$this->affectations->contains($affectation)) {
            $this->affectations[] = $affectation;
            $affectation->setUsers($this);
        }

        return $this;
    }

    public function removeAffectation(Affectaion $affectation): self
    {
        if ($this->affectations->contains($affectation)) {
            $this->affectations->removeElement($affectation);
            // set the owning side to null (unless already changed)
            if ($affectation->getUsers() === $this) {
                $affectation->setUsers(null);
            }
        }

        return $this;
    }

    

    



   


  
    

   
}

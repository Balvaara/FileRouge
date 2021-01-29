<?php

namespace App\Entity;

use App\Entity\Partenaire;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;


/**
 * @ApiResource(normalizationContext={"groups"={"comptes"}})
 * @ORM\Entity(repositoryClass="App\Repository\CompteRepository")
 * @ApiFilter(SearchFilter::class, properties={"numero": "exact", "id":"exact"})
 */
class Compte 
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"partenaire"})
     * @Groups({"comptes"})
     * @Groups({"depot"})
     * @Groups({"user"})
     * @Groups({"affectation"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"partenaire"})
     * @Groups({"comptes"})
     *  @Groups({"depot"})
     * @Groups({"affectation"})
     * @Groups({"user"})
     */
    private $numero;

    /**
     * @ORM\Column(type="integer")
     *  @Assert\Range(min = 500000)
     * @Groups({"comptes"})
     *  @Groups({"depot"})
     * @Groups({"partenaire"})
     */
    private $solde;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime
     * @var string A "Y-m-d H:i:s" formatted value
     * @Groups({"comptes"})
     *  @Groups({"depot"})
     */
    private $datecreate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comptes")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"comptes"})
     */
    private $usercreate;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Depot", mappedBy="comptes",cascade={"remove"})
     * @Groups({"comptes"})
     */
    private $depots;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Partenaire", inversedBy="comptes")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"comptes"})
     * @Groups({"user"})
     */
    private $partenaire;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction", mappedBy="comptesDep",cascade={"remove"})
     *  @ORM\JoinColumn(nullable=true)
     */
    private $transactions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction", mappedBy="compteRetraits",cascade={"remove"})
     *  @ORM\JoinColumn(nullable=true)
     */
    private $retraitTransaction;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Affectaion", mappedBy="comptes",cascade={"remove"})
     * @Groups({"comptes"})
     */
    private $affectaions;



    public function __construct()
    {
        $this->depots = new ArrayCollection();
        $this->transactions = new ArrayCollection();
        $this->retraitTransaction = new ArrayCollection();
        $this->affectaions = new ArrayCollection();
  
    }


   

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getSolde(): ?int
    {
        return $this->solde;
    }

    public function setSolde(int $solde): self
    {
        $this->solde = $solde;

        return $this;
    }

    public function getDatecreate(): ?\DateTimeInterface
    {
        return $this->datecreate;
    }

    public function setDatecreate(\DateTimeInterface $datecreate): self
    {
        $this->datecreate = $datecreate;

        return $this;
    }

    public function getUsercreate(): ?User
    {
        return $this->usercreate;
    }

    public function setUsercreate(?User $usercreate): self
    {
        $this->usercreate = $usercreate;

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
            $depot->setComptes($this);
        }

        return $this;
    }

    public function removeDepot(Depot $depot): self
    {
        if ($this->depots->contains($depot)) {
            $this->depots->removeElement($depot);
            // set the owning side to null (unless already changed)
            if ($depot->getComptes() === $this) {
                $depot->setComptes(null);
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
            $transaction->setComptesDep($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->contains($transaction)) {
            $this->transactions->removeElement($transaction);
            // set the owning side to null (unless already changed)
            if ($transaction->getComptesDep() === $this) {
                $transaction->setComptesDep(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getRetraitTransaction(): Collection
    {
        return $this->retraitTransaction;
    }

    public function addRetraitTransaction(Transaction $retraitTransaction): self
    {
        if (!$this->retraitTransaction->contains($retraitTransaction)) {
            $this->retraitTransaction[] = $retraitTransaction;
            $retraitTransaction->setCompteRetraits($this);
        }

        return $this;
    }

    public function removeRetraitTransaction(Transaction $retraitTransaction): self
    {
        if ($this->retraitTransaction->contains($retraitTransaction)) {
            $this->retraitTransaction->removeElement($retraitTransaction);
            // set the owning side to null (unless already changed)
            if ($retraitTransaction->getCompteRetraits() === $this) {
                $retraitTransaction->setCompteRetraits(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Affectaion[]
     */
    public function getAffectaions(): Collection
    {
        return $this->affectaions;
    }

    public function addAffectaion(Affectaion $affectaion): self
    {
        if (!$this->affectaions->contains($affectaion)) {
            $this->affectaions[] = $affectaion;
            $affectaion->setComptes($this);
        }

        return $this;
    }

    public function removeAffectaion(Affectaion $affectaion): self
    {
        if ($this->affectaions->contains($affectaion)) {
            $this->affectaions->removeElement($affectaion);
            // set the owning side to null (unless already changed)
            if ($affectaion->getComptes() === $this) {
                $affectaion->setComptes(null);
            }
        }

        return $this;
    }

    
  
}

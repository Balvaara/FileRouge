<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AffectaionRepository")
 * @ApiResource(normalizationContext={"groups"={"affectation"}})
 */
class Affectaion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"affectation"})
     * @Groups({"comptes"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Compte", inversedBy="affectaions")
     * @ORM\JoinColumn(nullable=false)
     *  @Groups({"affectation"})
     */
    private $comptes;

 

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="compteAffecte")
     *  @Groups({"affectation"})
     */
    private $userCon;

    /**
     * @ORM\Column(type="datetime")
     *  @Groups({"affectation"})
     * @Groups({"comptes"})
     */
    private $dateAfect;

    /**
     * @ORM\Column(type="datetime")
     *  @Groups({"affectation"})
     * @Groups({"comptes"})
     */
    private $dateFin;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="affectations")
     * @ORM\JoinColumn(nullable=false)
     *  @Groups({"affectation"})
     */
    private $users;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComptes(): ?Compte
    {
        return $this->comptes;
    }

    public function setComptes(?Compte $comptes): self
    {
        $this->comptes = $comptes;

        return $this;
    }

  

    public function getUserCon(): ?User
    {
        return $this->userCon;
    }

    public function setUserCon(?User $userCon): self
    {
        $this->userCon = $userCon;

        return $this;
    }

    public function getDateAfect(): ?\DateTimeInterface
    {
        return $this->dateAfect;
    }

    public function setDateAfect(\DateTimeInterface $dateAfect): self
    {
        $this->dateAfect = $dateAfect;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): self
    {
        $this->users = $users;

        return $this;
    }

   
}

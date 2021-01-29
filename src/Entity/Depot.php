<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(normalizationContext={"groups"={"depot"}})
 * @ORM\Entity(repositoryClass="App\Repository\DepotRepository")
 * 
 */
class Depot
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *   @Groups({"depot"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     *   @Groups({"depot"})
     * @Groups({"comptes"})
     */
    private $montant;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Compte", inversedBy="depots")
     * @ORM\JoinColumn(nullable=false)
     *  @Groups({"depot"})
     */
    private $comptes;

  

    /**
     * @ORM\Column(type="datetime")
     *  @Groups({"depot"})
     *  @Groups({"comptes"})
     */
    private $datedepot;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="depots")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"depot"})
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
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


    public function getDatedepot(): ?\DateTimeInterface
    {
        return $this->datedepot;
    }

    public function setDatedepot(\DateTimeInterface $datedepot): self
    {
        $this->datedepot = $datedepot;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}

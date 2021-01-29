<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TarifRepository")
 * @ApiResource()
 */
class Tarif
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $bonInf;

  
    /**
     * @ORM\Column(type="integer")
     */
    private $bornSup;

    /**
     * @ORM\Column(type="integer")
     */
    private $frais;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBonInf(): ?int
    {
        return $this->bonInf;
    }

    public function setBonInf(int $bonInf): self
    {
        $this->bonInf = $bonInf;

        return $this;
    }

   

    public function getBornSup(): ?int
    {
        return $this->bornSup;
    }

    public function setBornSup(int $bornSup): self
    {
        $this->bornSup = $bornSup;

        return $this;
    }

    public function getFrais(): ?int
    {
        return $this->frais;
    }

    public function setFrais(int $frais): self
    {
        $this->frais = $frais;

        return $this;
    }
}

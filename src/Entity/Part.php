<?php

namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PartRepository")
 * @ApiResource()
 */
class Part
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $etat;

    /**
     * @ORM\Column(type="float")
     */
    private $systeme;

    /**
     * @ORM\Column(type="float")
     */
    private $dep;

    /**
     * @ORM\Column(type="float")
     */
    private $ret;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtat(): ?float
    {
        return $this->etat;
    }

    public function setEtat(float $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getSysteme(): ?float
    {
        return $this->systeme;
    }

    public function setSysteme(float $systeme): self
    {
        $this->systeme = $systeme;

        return $this;
    }

    public function getDep(): ?float
    {
        return $this->dep;
    }

    public function setDep(float $dep): self
    {
        $this->dep = $dep;

        return $this;
    }

    public function getRet(): ?float
    {
        return $this->ret;
    }

    public function setRet(float $ret): self
    {
        $this->ret = $ret;

        return $this;
    }
}

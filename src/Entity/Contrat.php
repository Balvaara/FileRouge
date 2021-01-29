<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Annotation\ApiFilter;



/**
 * @ORM\Entity(repositoryClass="App\Repository\ContratRepository")
 *@ApiResource(normalizationContext={"groups"={"contrat"}},
 *     collectionOperations={"get"={"method"="GET"}},
 *     itemOperations={"get"={"method"="GET"}}
 * )
 *  @ApiFilter(SearchFilter::class, properties={"id": "exact"})
 */
class Contrat
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"partenaire"})
     * @Groups({"contrat"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups({"partenaire"})
     * @Groups({"contrat"})
     */
    private $numeroContrat;

    /**
     * @ORM\Column(type="string", type="text")
     *  @Groups({"contrat"})
     * @Groups({"partenaire"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", type="text")
     *  @Groups({"partenaire"})
     * @Groups({"contrat"})
     */
    private $therme;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroContrat(): ?string
    {
        return $this->numeroContrat;
    }

    public function setNumeroContrat(string $numeroContrat): self
    {
        $this->numeroContrat = $numeroContrat;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getTherme(): ?string
    {
        return $this->therme;
    }

    public function setTherme(string $therme): self
    {
        $this->therme = $therme;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Algorithm\Osms;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TransactionRepository")
 * @ApiFilter(SearchFilter::class, properties={"code": "exact", "id":"exact"})
 * @ApiResource(normalizationContext={"groups"={"transaction"}}))
 */
class Transaction
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"transaction"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     * @Groups({"transaction"})
     *  @Groups({"user"})
     */
    private $nomdep;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     * @Groups({"transaction"})
     */
    private $teldep;

    /**
     * @ORM\Column(type="integer",nullable=true)
     * @Groups({"transaction"})
     */
    private $montant;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     * @Groups({"transaction"})
     */
    private $datetransaction;

    /**
     * @ORM\Column(type="integer",nullable=true)
     * @Groups({"transaction"})
     */
    private $tarifs;

    
    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     * @Groups({"transaction"})
     */
    private $telrep;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     * @Groups({"transaction"})
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"transaction"})
     */
    private $piece;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"transaction"})
     */
    private $partEtat;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"transaction"})
     */
    private $partSysteme;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"transaction"})
     */
    private $partDep;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"transaction"})
     */
    private $partRet;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     * @Groups({"transaction"})
     */
    private $dateRet;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     * @Groups({"transaction"})
     */
    private $nomRecepteur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Compte", inversedBy="tranCompteDeps")
     *@ORM\JoinColumn(nullable=true)
     * @Groups({"transaction"})
     */
    private $comptesDep;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Compte", inversedBy="copmpteRets")
     *@ORM\JoinColumn(nullable=true)
     * @Groups({"transaction"})
     */
    private $compteRetraits;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="transactions")
     *@ORM\JoinColumn(nullable=true)
     * @Groups({"transaction"})
     */
    private $retaitUse;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="depTranUse")
     *@ORM\JoinColumn(nullable=true)
     *@Groups({"transaction"})
     */
    private $depotUse;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"transaction"})
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomdep(): ?string
    {
        return $this->nomdep;
    }

    public function setNomdep(string $nomdep): self
    {
        $this->nomdep = $nomdep;

        return $this;
    }

    public function getTeldep(): ?string
    {
        return $this->teldep;
    }

    public function setTeldep(string $teldep): self
    {
        $this->teldep = $teldep;

        return $this;
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

    public function getDatetransaction(): ?\DateTimeInterface
    {
        return $this->datetransaction;
    }

    public function setDatetransaction(\DateTimeInterface $datetransaction): self
    {
        $this->datetransaction = $datetransaction;

        return $this;
    }

   

    public function getTarifs(): ?int
    {
        return $this->tarifs;
    }

    public function setTarifs(int $tarifs): self
    {
        $this->tarifs = $tarifs;

        return $this;
    }


  

    public function getTelrep(): ?string
    {
        return $this->telrep;
    }

    public function setTelrep(string $telrep): self
    {
        $this->telrep = $telrep;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getPiece(): ?string
    {
        return $this->piece;
    }

    public function setPiece(string $piece): self
    {
        $this->piece = $piece;

        return $this;
    }

    public function getPartEtat(): ?float
    {
        return $this->partEtat;
    }

    public function setPartEtat(float $partEtat): self
    {
        $this->partEtat = $partEtat;

        return $this;
    }

    public function getPartSysteme(): ?float
    {
        return $this->partSysteme;
    }

    public function setPartSysteme(float $partSysteme): self
    {
        $this->partSysteme = $partSysteme;

        return $this;
    }

    public function getPartDep(): ?float
    {
        return $this->partDep;
    }

    public function setPartDep(float $partDep): self
    {
        $this->partDep = $partDep;

        return $this;
    }

    public function getPartRet(): ?float
    {
        return $this->partRet;
    }

    public function setPartRet(float $partRet): self
    {
        $this->partRet = $partRet;

        return $this;
    }

    public function getDateRet(): ?\DateTimeInterface
    {
        return $this->dateRet;
    }

    public function setDateRet(\DateTimeInterface $dateRet): self
    {
        $this->dateRet = $dateRet;

        return $this;
    }

    public function getNomRecepteur(): ?string
    {
        return $this->nomRecepteur;
    }

    public function setNomRecepteur(string $nomRecepteur): self
    {
        $this->nomRecepteur = $nomRecepteur;

        return $this;
    }

    public function getComptesDep(): ?Compte
    {
        return $this->comptesDep;
    }

    public function setComptesDep(?Compte $comptesDep): self
    {
        $this->comptesDep = $comptesDep;

        return $this;
    }


    public function getCompteRetraits(): ?Compte
    {
        return $this->compteRetraits;
    }

    public function setCompteRetraits(?Compte $compteRetraits): self
    {
        $this->compteRetraits = $compteRetraits;

        return $this;
    }

    public function getRetaitUse(): ?User
    {
        return $this->retaitUse;
    }

    public function setRetaitUse(?User $retaitUse): self
    {
        $this->retaitUse = $retaitUse;

        return $this;
    }

    public function getDepotUse(): ?User
    {
        return $this->depotUse;
    }

    public function setDepotUse(?User $depotUse): self
    {
        $this->depotUse = $depotUse;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function sendMessage($data)
    {
        $message="Bienvenue dans KayPay Transfert"." ".$data->getNomdep()." ".
        "Vous a envoyé"." ".$data->getMontant().
        "Vous pouvez le retirer dans nos agences Yonema";
        $config=array(
            'clientId' =>'gmoG0050PxQAEUYapHM9ZsEVaTULmBQp',
            'clientSecret' =>'M2QDfeGi8lav85NQ'
        );
                
        $osms = new Osms($config);

        $osms->setVerifyPeerSSL(false);

        // retrieve an access token
        $response = $osms->getTokenFromConsumerKey();
            
        if (!empty($response['access_token'])) {
            $receiverAddress = 'tel:+221'.$data->getTelrep();
            ($osms->sendSMS( $receiverAddress, $message));
        } else {
            // error
        }
                    
     }
}

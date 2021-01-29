<?php

namespace App\Generateur;

use App\Entity\Compte;
use App\Repository\CompteRepository;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

class GenererNumCompte{

    private $numero;


    public function __construct(CompteRepository $CompteRepository)
    {
      

        $last=$CompteRepository->findOneBy([],['id'=>'desc']);

        if ($last!=null) {

            $lastId=$last->getId();

            $this->numero=sprintf("%'.02d",$lastId +1);
        }
        else{
            $this->numero=sprintf("%'.02d",1);
        }

    }

    public function generer(){
        $indece='CMP';
    

        $date=new \DateTime();

        return $indece.date_format($date, 'Y').$this->numero;
    }
}
<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Entity\Transaction;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    /**
     * @Route("/api")
     */
class PartController extends AbstractController
{
    /**
     * @Route("/part", name="part")
     */
    public function index()
    {
     
    }


            /**
             * @Route("/listerComptesByPartener", methods={"GET"})
            */
            public function getCompte()
            {
                
                $repo = $this->getDoctrine()->getRepository(Compte::class);
                $compte = $repo->findAll();
                
                $data = [];
                $i= 0;
                $ucsercon =$this->getUser();
                
                // dd($ucsercon);
                if($ucsercon->getProfil()->getLibelle() ==  "PARTENAIRE")
                {
                    foreach($compte as $comptes)
                    {
                        if($ucsercon->getPartenaire()->getId() === $comptes->getPartenaire()->getId())
                        {
                            $data[$i]=$comptes;
                            $i++;
                        }
                        
                    }
                //  dd($data);
                         
                 return $this->json($data, 200);
                }
           
            }
        
}

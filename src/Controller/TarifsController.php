<?php

namespace App\Controller;

use App\Entity\Tarif;
use App\Repository\TarifRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
/**
 * @Route("/api")
 */
class TarifsController extends AbstractController
{
    /**
     * @Route("/tarisByMontant/{montant}", methods={"GET"})
     */

    public function Frais($montant)
    {
        //  $values=json_decode($request->getContent());
        $frai = $this->getDoctrine()->getRepository(Tarif::class);
        $all = $frai->findAll();
    //    var_dump($all); die;
        foreach($all as $val)
        {
            
            if($val->getBonInf() <= $montant && $val->getBornSup()>= $montant)
            {
                
                return $this->json($val); 
            }
        }
     

    }

}

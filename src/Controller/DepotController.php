<?php

namespace App\Controller;

use App\Entity\Depot;
use App\Entity\Compte;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
/**
 * @Route("/api")
 */
class DepotController extends AbstractController
{

    public function numero( string $numero)
    {
     
        $compte = $this->getDoctrine()->getRepository(Compte::class);
        $comptes = $compte->findAll();
        //  dd($partenaire); die;
         foreach ($comptes as  $value)
            {
             
            if($value->getNumero()===$numero){
               
                return true;
               
            }
            }
            
            
        
     }

         /**
         * @Route("/compte/fairedepot", name="fairedepot", methods={"POST"})
         */

        public function faire_depot(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
        {
           $users = $this->getUser();
        //    $this-> denyAccessUnlessGranted(['ROLE_SUP_ADMIN','ROLE_ADMIN','ROLE_CAISSIER']);
   
           
           $val = json_decode($request->getContent());

           $dateday=new \DateTime();

           $depot = $serializer->deserialize($request->getContent(), Depot::class, 'json');

           $compte = $serializer->deserialize($request->getContent(), Compte::class, 'json');


           $repCompt= $this->getDoctrine()->getRepository(Compte::class);
           // $depCompte = $repCompt->findOneBy([],['id'=>'desc']);
            if ($users->getProfil()->getLibelle()==("SUP_ADMIN" || "ADMIN" || "CAISSIER"))
             {
         
                if ($this->numero($val->numero)==true)
                    {
                        
                        $part =$this->getDoctrine()->getRepository(Compte::class);
                        $lignePart=$part->findOneByNumero($val->numero);
                        
                        

                    if($val->montant > 0)
                    {
                
                        
    
                    
                            $depot->setDatedepot($dateday);
                            $depot->setMontant($val->montant);
                            $depot->setComptes($lignePart);
                            $depot->setUser($users);
                            $entityManager->persist($depot);
                            $entityManager->flush();
                            //mis a jour le depot
                                $nouveau = ($val->montant+$depot->getComptes()->getSolde());
                                $repCompt=$depot->getComptes()->setSolde($nouveau);
                                $entityManager->persist($repCompt);
                                $entityManager->flush();
            
                                $data = [
                                    'status' => 200,
                                    'message' => 'vous avez faire un depot de:'.$val->montant.' le: '
                                    .date_format($depot->getDatedepot(), 'Y-m-d H:i:s').' Dans Le Compte '.$repCompt->getNumero().' votre Nouneau Solde est: '.$repCompt->getSolde()
                                ];
                        
                                return new JsonResponse($data, 200);
            
                        }else{
                            $data = [
                                'status' => 500,
                                'message' => 'Montant Ivadide'
                            ];
                    
                            return new JsonResponse($data, 500);
                        }
                        
                        }else{
                            $data = [
                                'status' => 200,
                                'message' => 'Numero Ivadide'
                            ];
                    
                            return new JsonResponse($data, 200);
                        }
                        
            }
            $data = [
                'status' => 200,
                'message' => 'Vous ne pouvez pas faire un  depot'
            ];
    
            return new JsonResponse($data, 200);
           }
   
   
}

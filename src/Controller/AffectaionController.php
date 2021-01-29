<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Compte;
use App\Entity\Affectaion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
/**
 * @Route("/api")
 */
class AffectaionController extends AbstractController
{



             /**
             * @Route("/listerAffectationByPartener",name="my", methods={"GET"})
            */
            public function getAffectByPartener()
            {
                
                $repo = $this->getDoctrine()->getRepository(Affectaion::class);
                $affect = $repo->findAll();
                
                $data = [];
                $i= 0;
                $ucsercon =$this->getUser();
                //dd($ucsercon);
                if($ucsercon->getRoles() ==  ["ROLE_PARTENAIRE"] )
                {
                    foreach($affect as $affects)
                    {
                        if($ucsercon->getId() === $affects->getUserCon()->getId())
                        {
                            $data[$i]=$affects;
                            $i++;
                        }
                        
                    }
                    //  dd($data);
                         
                 return $this->json($data, 200);
                }
           
            }


            /**
             * @Route("/listerComptesByPartener", name="listecomptes", methods={"GET"})
            */
            public function getCompte()
            {
                
                $repo = $this->getDoctrine()->getRepository(Compte::class);
                $compte = $repo->findAll();
                
                $data = [];
                $i= 0;
                $ucsercon =$this->getUser();
                
                // dd($ucsercon);
                // if($ucsercon->getProfil()->getLibelle() ==  "PARTENAIRE")
                // {
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
                // }
           
            }
        
   

             /**
             * @Route("/listerUserByPartener", name="listeuserpart", methods={"GET"})
            */
            public function getUserByPartener()
            {
                
                $repo = $this->getDoctrine()->getRepository(User::class);
                $user = $repo->findAll();
                
                $data = [];
                $i= 0;
                $ucsercon =$this->getUser();
                //dd($ucsercon);
                if($ucsercon->getRoles() ==  ["ROLE_PARTENAIRE"])
                {
                    foreach($user as $users)
                    {
                        if($ucsercon->getPartenaire() === $users->getPartenaire() && $users->getRoles()!==["ROLE_PARTENAIRE"])
                        {
                            if ($users->getPhoto()==null) {
                                $users->setPhoto(null);
                            }
                            else
                        $users->setPhoto(base64_encode(stream_get_contents($users->getPhoto())));
                       
                            $data[$i]=$users;
                            $i++;
                        }
                        
                    }
                    // dd($data);
                         
                 return $this->json($data, 200);
                }
           
            }


    /**
     * @Route("/compte/AffectationCompte", methods={"POST"})
     */

        public function Affectaion(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
        {
            

        // $this-> denyAccessUnlessGranted(['ROLE_PARTENAIRE']);

            $users = $this->getUser();

                $repCompt= $this->getDoctrine()->getRepository(Compte::class);
                $CompteAfect = $repCompt->findAll();

                $affects= $this->getDoctrine()->getRepository(Affectaion::class);
                $Afect = $affects->findAll();

            $affect = $serializer->deserialize($request->getContent(), Affectaion::class, 'json');


            //verifier que le partenaire connecte a un compte
            // dd($affect);
                foreach ($Afect as $value)
                 {
                
                    
                    if (($value->getUsers() == $affect->getUsers() && $value->getDateFin() >
                    $affect->getDateAfect()) )
                     {
                        
                        $data = [
                            'status' => 500,
                            'message' => 'Desolé Cet Utisalisateur Sa Date D\'affectation n\'est pas encore Termineé '
                        ];
                
                        return new JsonResponse($data, 200);
                    }
                }
                   
                // var_dump($val->getNumero());die;
               
                  
                        
                
            
                    $affect->setComptes($affect->getComptes());
                    $affect->setUsers($affect->getUsers());
                    $affect->setUserCon($users);
                    $entityManager->persist($affect);
                    $entityManager->flush();

                    $data = [
                        'status' => 201,
                        'message' => 'le Compte Sous Le Numero  '.$affect->getComptes()->getNumero().' Est Affecte au '.
                        $affect->getUsers()->getNomComplet().' Du '. date_format($affect->getDateAfect(),'Y-m-d H:i:s').' Au '
                        .date_format($affect->getDateFin(), 'Y-m-d H:i:s')
                
            ];
    
            return new JsonResponse($data, 200);
           
       
      
            
       
     }

}
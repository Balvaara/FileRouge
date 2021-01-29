<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Depot;
use App\Entity\Compte;
use App\Entity\Partenaire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
/**
 * @Route("/api")
 */
class PartenaireController extends AbstractController
{

            /**
             * @Route("/UserPartener", methods={"GET"})
            */
            public function getUserByPartener()
            {
                
                $repo = $this->getDoctrine()->getRepository(User::class);
                $user = $repo->findAll();
                
                $data = [];
                $i= 0;
                $ucsercon =$this->getUser();
                //dd($ucsercon);
           
                    foreach($user as $users)
                    {
                        if($users->getRoles()==["ROLE_PARTENAIRE"])
                        {
                            $data[$i]=$users;
                            $i++;
                        }else{
                            $data = [
                                'status' => 401,
                                'message' => 'Pas d\'acces'
                                    ];
                                return new JsonResponse($data, 401);

                        }
                        
                    }
                    // dd($data);
                         
                 return $this->json($data, 200);
                
           
            }

             /**
             * @Route("/users/partenaire/{id}", methods={"GET"})
            */
        public function EtatUse($id )
        {
                

            $rep = $this->getDoctrine()->getRepository(User::class);
            $status='';
            $user=$rep->findAll();
            $users = $this->getUser();
        

            if ($users->getProfil()->getLibelle()=="SUP_ADMIN" || $users->getProfil()->getLibelle()=="ADMIN")
            {
                foreach ($user as $value) {
                    
                    $part =$this->getDoctrine()->getRepository(User::class);
                    $lignePart=$part->showUsers($id);
                    $count=count($lignePart);
                    //  dd($count);
                    if ($count==1)
                     {
                        foreach ($lignePart as $val) {
                            // dd($val->getUsername());
                        if ($val->getIsActive()==true) {

                        $val->setIsActive(false);
                        $status='Bloqué';

                        }else{
                            $val->setIsActive(true);
                            $status='Debloqué';
                        }
                       
                        //  dd( $val->getIsActive());
                         }
                         $entityManager=$this->getDoctrine()->getManager();
                         $entityManager->persist($val);
                         $entityManager->flush();
         
                         $data = [
                         'status' => 201,
                         'message' => 'Cet Partenaire est '.$status
                             ];
                         return new JsonResponse($data, 200);
                         
                    }else{

                    
                     
                
                    foreach ($lignePart as $val) {
                            // dd($val->getUsername());
                        if ($val->getIsActive()==true) {

                        $val->setIsActive(false);
                        $status='Bloqué';

                        }else{
                            $val->setIsActive(true);
                            $status='Debloqué';
                        }
                       
                        //  dd( $val->getIsActive());
                    }
                }
                    $entityManager=$this->getDoctrine()->getManager();
                    $entityManager->persist($val);
                    $entityManager->flush();
    
                    $data = [
                    'status' => 201,
                    'message' => 'Cet Partenaire est '.$status.' Et tous ses Utilisateurs'
                        ];
                    return new JsonResponse($data, 200);
                    
                   
                    }    
                }
                $data = [
                    'status' => 201,
                    'message' => 'Vous n\'avez pas les authorisations nessessaires'
                        ];
                    return new JsonResponse($data, 200);
                
           
      }

        /**
         * @Route("/nbparteners",  methods={"GET"})
        */

        public function NbParteners()
        {

            $compte = $this->getDoctrine()->getRepository(Partenaire::class);
            $comptes = $compte->findAll();
            //  dd($partenaire); die;
            $cpt=0;
            foreach ($comptes as  $value)
                {
                    $cpt++;
                
                }
                return $this->json($cpt);
        }

        /**
         * @Route("/nbuserparener",  methods={"GET"})
        */

        public function NbPartener()
        {

           
            $users = $this->getUser();
            //  dd($partenaire); die;
            $part =$this->getDoctrine()->getRepository(User::class);
             $lignePart=$part->showUsers($users->getPartenaire()->getId());
             $count=count($lignePart);
            
           
                return $this->json($count-1);
        }
    
            /**
         * @Route("/nombreComptes",  methods={"GET"})
        */

        public function Comptes()
        {
            $ucsercon =$this->getUser();
            $data = [];
                $i= 0;
            $compte = $this->getDoctrine()->getRepository(Compte::class);
            $comptes = $compte->findAll();
            //  dd($partenaire); die;
            $cpt=0;
            foreach ($comptes as  $value)
                {
                    if ($ucsercon->getPartenaire()===$value->getPartenaire()) {
                        $cpt++;
                    }
                    
                
                }
                return $this->json($cpt);
        }

             /**
             * @Route("/userConByPartener",methods={"GET"})
             */
            public function getCon()
            {
                $data=[];
                $i=0;
                $ucsercon =$this->getUser();
                $rep = $this->getDoctrine()->getRepository(User::class);
                $user=$rep->findAll();
                foreach ($user as  $value)
                {
                    if ($ucsercon->getId()===$value->getId()) {
                        if ($ucsercon->getPhoto()==null) {
                            $ucsercon->setPhoto(null);
                        }else{
                        $ucsercon->setPhoto(base64_encode(stream_get_contents($ucsercon->getPhoto())));
    
                        }
                       
                    }
                    
                
                }
                return $this->json($ucsercon, 200);
                    
                    
                    // dd($user->getPhoto);
                    
        }
        
        /**
             * @Route("/supprimer/partenaire/{id}", methods={"DELETE"})
            */
            public function Remove($id )
            {
                    
    
                $rep = $this->getDoctrine()->getRepository(User::class);
                $status='Suprimé';
                $user=$rep->findAll();
                $users = $this->getUser();
            
              

                // dd($lignecompte);
              

                $part =$this->getDoctrine()->getRepository(Partenaire::class);
                $lignePart=$part->showPartener($id);

                if ($users->getProfil()->getLibelle()=="SUP_ADMIN" || $users->getProfil()->getLibelle()=="ADMIN")
                {

                        // $count=count($ligneuser);
                         
                        foreach ($lignePart as $val) {
                          
                            $entityManager=$this->getDoctrine()->getManager();
                            $entityManager->remove($val);
                             $entityManager->flush();
           
                       
                         }
                        
                        $data = [
                        'status' => 201,
                        'message' => 'Cet Partenaire est '.$status.' Et tous ses Utilisateurs et Tous ses Comptes'
                            ];
                        return new JsonResponse($data, 200);
                        
                       
                        
                }
                    $data = [
                        'status' => 201,
                        'message' => 'Vous n\'avez pas les authorisations nessessaires'
                            ];
                        return new JsonResponse($data, 200);
                    
               
          }

}

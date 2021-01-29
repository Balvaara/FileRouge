<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api")
 */
class UserController extends AbstractController
{
    


    private $encoder;
 
    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }

    /**
     * @Route("/user", name="add_user", methods={"POST"})
     */

    public function new(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {

        
        $users = $this->getUser();

        $user =$serializer->deserialize($request->getContent(), User::class, 'json');

        // $this-> denyAccessUnlessGranted(['ROLE_SUP_ADMIN','ROLE_ADMIN','ROLE_PARTENAIRE']);

        $errors = $validator->validate($user);
        if(count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json'
            ]);
            }
            if ($users->getProfil()->getLibelle()=='PARTENAIRE' ||
            $users->getProfil()->getLibelle()=='ADMIN_PARTENAIRE') 
            {
                $user->setPartenaire($users->getPartenaire());
                $user->setPassword($this->encoder->encodePassword($user, $user->getPassword()));
                $user->setRoles(["ROLE_".strtoupper($user->getProfil()->getLibelle())]);
                $entityManager->persist($user);
                $entityManager->flush();
          
            $data = [
                'status' => 200,
                'message' => 'Utilisateur '.$user->getNomComplet().' cree avec succes'
            ];
            return new JsonResponse($data, 201);
        }else{
            
            
            $user->setPassword($this->encoder->encodePassword($user, $user->getPassword()));
            $user->setRoles(["ROLE_".strtoupper($user->getProfil()->getLibelle())]);
            $entityManager->persist($user);
            $entityManager->flush();

            $data = [
                'status' => 201,
                'message' => 'Utilisateur '.$user->getNomComplet().' cree avec succes'
            ];
            return new JsonResponse($data, 200);
        }

    }

            /**
             * @Route("/users/status/{id}", methods={"GET"})
            */
        public function EtatUse($id)
        {
               
    
            $rep = $this->getDoctrine()->getRepository(User::class);
            $status='';
            $user=$rep->find($id);
            $users = $this->getUser();
        if ($users->getProfil()->getLibelle()=="SUP_ADMIN" || $users->getProfil()->getLibelle()=="ADMIN"
            || $users->getProfil()->getLibelle()=="PARTENAIRE")
        {
            if ($user->getIsActive()==true) 
            {
            if ($user->getProfil()->getLibelle() != "SUP_ADMIN") 
                {
                $user->setIsActive(false);
                    $status=' Bloqué';
                }else
                {
                    $data = [
                        'status' => 500,
                        'message' => 'Impossible de Bloquer '.$user->getUsername(). ' Car Son Profil est '
                        .$user->getProfil()->getLibelle()
                            ];
                            return new JsonResponse($data, 200);
        
                    }
                        
          }
           else{
              $user->setIsActive(true);
                  $status='Debloqué';
             }
                    
                 $entityManager=$this->getDoctrine()->getManager();
                 $entityManager->persist($user);
                 $entityManager->flush();
    
                    $data = [
                    'status' => 201,
                    'message' =>'L\'utilisateur '. $user->getUsername().' est '.$status
                        ];
                    return new JsonResponse($data, 200);
                        
            }else{
                $data = [
                    'status' => 401,
                    'message' => 'Vous n\'avez pas les autorisation nessessaires veuillez vous rapprochez de Votre Administrateurs Svp' 
                ];
                 return new JsonResponse($data, 200);
    
            }
        }
   
    
            
    
    
            /**
             *@Route("/listerUsers", methods={"GET"})
            */
            public function getUsers()
            {
                
                $repo = $this->getDoctrine()->getRepository(User::class);
                $users = $repo->findAll();
               
                
                $data = [];
                $i= 0;
                $ucsercon =$this->getUser();
                //dd($ucsercon);
                if($ucsercon->getProfil()->getLibelle()  ==="SUP_ADMIN")
                {
                    foreach($users as $user)
                    {
                        if($user->getProfil()->getLibelle() === 'ADMIN' || $user->getProfil()->getLibelle() === 'CAISSIER' )
                        {
                            //  dd(base64_encode($user->getPhoto()));
                                if ($user->getPhoto()==null) {
                                    $user->setPhoto(null);
                                }
                                else
                            $user->setPhoto(base64_encode(stream_get_contents($user->getPhoto())));
                           

                            $data[$i]=$user;
                            $i++;
                        }
                        
                    }
                }
                elseif($ucsercon->getProfil()->getLibelle()  ===  "ADMIN")
                {
                    
                    foreach($users as $user)
                    {
                        if($user->getProfil()->getLibelle() === "CAISSIER")
                        {
                            if ($user->getPhoto()==null) {
                                $user->setPhoto(null);
                            }
                            else{
                        $user->setPhoto(base64_encode(stream_get_contents($user->getPhoto())));
                            }
                        $data[$i]=$user;
                        $i++;
                        
                    }
                        
                    }
                }
               elseif($ucsercon->getProfil()->getLibelle() ===  "PARTENAIRE")
                {
                    
                    foreach($users as $user)
                    {
                        if($user->getProfil()->getLibelle() === "ADMIN_PARTENAIRE" ||
                         $user->getProfil()->getLibelle() === "CAISSIER_PARTENAIRE"
                         && $ucsercon->getPartenaire()->getId()===$user->getPartenaire()->getId())
                        {
                            if ($user->getPhoto()==null) {
                                $user->setPhoto(null);
                            }
                            else{
                         $user->setPhoto(base64_encode(stream_get_contents($user->getPhoto())));

                            }
                       
                        $data[$i]=$user;
                        $i++;
                     
                        }
                        
                    }
                }
        
                elseif($ucsercon->getProfil()->getLibelle()  === "ADMIN_PARTENAIRE")
                {
                   
                    foreach($users as $user)
                    {
                        if((  $user->getProfil()->getLibelle() == "CAISSIER_PARTENAIRE")
                         && $ucsercon->getPartenaire()===$user->getPartenaire())
                        {
                            if ($user->getPhoto()==null) {
                                $user->setPhoto(null);
                            }
                            else{
                         $user->setPhoto(base64_encode(stream_get_contents($user->getPhoto())));

                            }
                       
                        $data[$i]=$user;
                        $i++;
                     
                        }
                        
                    }
                }
                else
                {
                    $data = [
                        'status' => 401,
                        'message' => 'Désolé access non autorisé !!!'
                        ];
                        return new JsonResponse($data, 401);
                    
                }
                return $this->json($data, 201);
            }
        
            /**
             * @Route("/delete/{id}", name="delete", methods={"DELETE"})
             */
            public function delete($id)
            {
                $users = $this->getUser();

                $rep = $this->getDoctrine()->getRepository(User::class);
                $status='';
                $user=$rep->find($id);
                $entityManager=$this->getDoctrine()->getManager();
                $entityManager->remove($user);
                $entityManager->flush();
                $data = [
                    'status' => 401,
                    'message' => 'L\'utilisateur Suprime avec Success !!!'
                    ];
                    return new JsonResponse($data, 200);
            }


             /**
             * @Route("/edite/{id}", name="edite", methods={"PUT"})
             */
    public function update($id,Request $request,UserPasswordEncoderInterface $encoder, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        $userUpdate = $entityManager->getRepository(User::class)->find($id);
        
        $data =json_decode($request->getContent());
        

        
//    dd($data);
$user =$serializer->deserialize($request->getContent(), User::class, 'json');

        $errors = $validator->validate($userUpdate);
        if(count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json'
            ]);
        }
        $userUpdate->setUsername($data->username);
        $userUpdate->setPassword($this->encoder->encodePassword($userUpdate, $data->password));
         $userUpdate->setProfil($user->getProfil());
         $userUpdate->setNomComplet($data->nomComplet);


        $entityManager->persist($userUpdate);
        $entityManager->flush();
        $data = [
            'status' => 200,
            'message' => 'Utilisateur Modifié avec success'
        ];
        return new JsonResponse($data);
    }


            /**
             * @Route("/users/id/{id}",  methods={"GET"})
             */
            public function getById($id, EntityManagerInterface $entityManager)
            {
                $userUpdate = $entityManager->getRepository(User::class)->find($id);
                
                if ($userUpdate->getPhoto()==null) {
                    $userUpdate->setPhoto(null);
                }else{
                $userUpdate->setPhoto(base64_encode(stream_get_contents($userUpdate->getPhoto())));
                    
                }
                
                return $this->json($userUpdate, 200);
        //    
            }
    
             /**
             * @Route("/userCon",methods={"GET"})
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
        }


            /**
             * @Route("/NbuserSys",methods={"GET"})
             */
            public function getUsersSys()
            {
               
                $data=0;
                $ucsercon =$this->getUser();
                $rep = $this->getDoctrine()->getRepository(User::class);
                $user=$rep->findAll();
                foreach ($user as  $value)
                {
                   
                       $data++;
                
                }
                return $this->json($data, 200);
        }
            

}

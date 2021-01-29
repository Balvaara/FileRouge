<?php

namespace App\Controller;

use App\Entity\Role;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
/**
 *@Route("/api")
 */
class RoleController extends AbstractController
{
    private $tokenStorage;
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

             /**
             * @Route("/listerRoles", methods={"GET"})
            */
            public function getRoles(Request $request, EntityManagerInterface $entityManager)
            {
                
                $repo = $this->getDoctrine()->getRepository(Role::class);
                $roles = $repo->findAll();
                
                $data = [];
                $i= 0;
                $rolesUser =$this->getUser();
                //dd($rolesUser);
                if($rolesUser->getRoles() ==  ["ROLE_SUP_ADMIN"])
                {
                    foreach($roles as $role)
                    {
                        if($role->getLibelle() === 'ADMIN' || $role->getLibelle() === 'CAISSIER')
                        {
                            $data[$i]=$role;
                            $i++;
                        }
                        
                    }
                }
                elseif($rolesUser->getRoles() ===  ["ROLE_ADMIN"])
                {
                    
                    foreach($roles as $role)
                    {
                        if($role->getLibelle() === 'CAISSIER')
                        {
                            $data[$i]=$role;
                            $i++;
                        }
                        
                    }
                }
               elseif($rolesUser->getRoles() ===  ["ROLE_PARTENAIRE"])
                {
                    
                    foreach($roles as $role)
                    {
                        if($role->getLibelle() === 'ADMIN_PARTENAIRE' || $role->getLibelle() === 'CAISSIER_PARTENAIRE')
                        {
                            $data[$i]=$role;
                            $i++;
                        }
                        
                    }
                }
        
                else if($rolesUser->getRoles() === ["ROLE__ADMIN_PARTENAIRE"])
                {
                   
                    foreach($roles as $role)
                    {
                        if($role->getLibelle() === 'CAISSIER_PARTENAIRE')
                        {
                            $data[$i]=$role;
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
                    
                }
                
             return $this->json($data, 200);
            }


            
        
 }
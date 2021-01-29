<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Entity\Depot;
use App\Entity\Compte;
use App\Entity\Contrat;
use App\Entity\Affectaion;
use App\Entity\Partenaire;
use App\Generateur\GenererNumCompte;
use App\Repository\CompteRepository;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api")
 */
class CompteController extends AbstractController
{
    private $encoder;
    // private $num;
    
   
 
    public function __construct(UserPasswordEncoderInterface $encoder) 
    {
        $this->encoder = $encoder;
    }

   
    public function Ninea($ninea)
    {
     
        $part = $this->getDoctrine()->getRepository(Partenaire::class);
        $partenaire = $part->findAll();
        //  dd($partenaire); die;
         foreach ($partenaire as  $value)
            {
            if ($ninea==$value->getNinea()){
               
                return true;
            }
            }
        
     }
                 

          
     /**
     * @Route("/compte/partenaire_exist", name="add_compte", methods={"POST"})
     */
   public function partenaier_exist(Request $request,GenererNumCompte $generer, SerializerInterface $serializer,CompteRepository $CompteRepository, EntityManagerInterface $entityManager, ValidatorInterface $validator)
   {
    // $this-> denyAccessUnlessGranted(['ROLE_SUP_ADMIN','ROLE_ADMIN']);
         //utilisateur qui connecte
         $users= $this->getUser();

         $date=new \DateTime();

         $compte = $serializer->deserialize($request->getContent(), Compte::class, 'json');
         $depot = $serializer->deserialize($request->getContent(), Depot::class, 'json');

       $val = json_decode($request->getContent());

       $errors = $validator->validate($compte);
       if(count($errors)) {
           $errors = $serializer->serialize($errors, 'json');
           return new Response($errors, 500, [
               'Content-Type' => 'application/json'
           ]);
       }


    //    if( $this->Ninea($val->ninea)==true)
    //    {
           $part =$this->getDoctrine()->getRepository(Partenaire::class);
           $lignePart=$part->findOneByNinea($val->ninea);
         
             

           $compte->setNumero($generer->generer());
           $compte->setDatecreate($date);
           $compte->setUsercreate($users);
           $compte->setPartenaire($lignePart);
           $entityManager->persist($compte);
           $entityManager->flush();


             //Faire Un Depot
            $depot->setDatedepot($date);
            $depot->setMontant($compte->getSolde());
            $depot->setComptes($compte);
            $depot->setUser($users);
            $entityManager->persist($depot);
            $entityManager->flush();
              
            $data = [
                'status' => 201,
                'message' => 'Nouveau compte cree Pour Le Partenaire qui a le Ninea '. $lignePart->getNinea().
                ' avec Le Numero: '.$compte->getNumero()
            ];
            return new JsonResponse($data, 200);

    //    }
    //         $data = [
    //             'status' => 500,
    //             'message' => 'Desolé Cet Partenaire N\'existe Pas'
    //         ];
    //         return new JsonResponse($data, 200);

    }
            
   		
   



    /**
     * @Route("/compte/nouveau_partenaire", methods={"POST"})
     */

    public function newPartenaire(Request $request,GenererNumCompte $generer, SerializerInterface $serializer,CompteRepository $CompteRepository, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this-> denyAccessUnlessGranted(['ROLE_SUP_ADMIN','ROLE_ADMIN']);
        
        $users = $this->getUser();
        
        // $values=json_decode($request->getContent());
        $date=new \DateTime('now');
        


         $compte = $serializer->deserialize($request->getContent(), Compte::class, 'json');
         $user = $serializer->deserialize($request->getContent(), User::class, 'json');
         $contrat = $serializer->deserialize($request->getContent(), Contrat::class, 'json');


         $depot = $serializer->deserialize($request->getContent(), Depot::class, 'json');
         $partenaire = $serializer->deserialize($request->getContent(), Partenaire::class, 'json');

        $errors = $validator->validate($compte);
        if(count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json'
            ]);
        }
   
        //recuperer le fichier du contrat
        $fichier= '/var/www/html/FileRouge/public/contrat.txt';
                
        $open=[];
        if (file_exists($fichier)) {
            if (false !== $haldal= @fopen($fichier,'r')) {
                while (($word=fgets($haldal)) !==false) {
                    $open[]=$word;
                    
                }
              
                fclose($haldal);
            }else{
                $open[]='pas de liste';
            }
        }
        else{
            $open[]='pas trouve';
        }

        $data=implode($open);
    //    $rech= preg_match("#ALPHA#", $data);
    $rech= str_replace("ALPHA",$user->getNomComplet(),$data);
        //  dd($rech);

        //Insertion Contrat 
      
        $contrat->setNumeroContrat('CR'.rand (1, 1000));
        $contrat->setLibelle('Contrat d\'engagement de M(ms): '.$user->getNomComplet());
        $contrat->setTherme($rech);
        $entityManager->persist($contrat);
        $entityManager->flush();

        //  dd(($data));
                //   $f= fopen("contrat.txt","a");
				// fwrite($f,$contrat->getNumeroContrat()."\n");
				
				// fwrite($f,$contrat->getLibelle().$partenaire->getNinea()."\n");
				
				// fwrite($f,$contrat->getTherme().PHP_EOL);
				// fclose($f);



         //Insertion Contrat 

        $partenaire->setContrats($contrat);
        $entityManager->persist($partenaire);
        $entityManager->flush();

       
        //Insertion Partenaire Utilisateur

        $part =$this->getDoctrine()->getRepository(Role::class);
        $lignePart=$part->findOneByLibelle("PARTENAIRE");
        
        // dd($lignePart);

        $user->setPassword($this->encoder->encodePassword($user,$user->getPassword()));
        $user->setRoles(["ROLE_PARTENAIRE"]);
        $user->setIsActive(true);
        $user->setPartenaire($partenaire);
        $user->setProfil($lignePart);
        $entityManager->persist($user);
        $entityManager->flush();

       
        

        

            
        //Cree Compte Partenaire
        $compte->setNumero($generer->generer());
        $compte->setDatecreate($date);
        $compte->setUsercreate($users);
        $compte->setPartenaire($partenaire);
        $entityManager->persist($compte);
        $entityManager->flush();

            
       
        $depot->setDatedepot($date);
        $depot->setMontant($compte->getSolde());
        $depot->setComptes($compte);
        $depot->setUser($users);
        $entityManager->persist($depot);
        $entityManager->flush();
        
        $data = [
            'status' => 200,
            'message' => 'Compte cree avec le Numero: '.$compte->getNumero().' Pour Le Partenaire '.
            $user->getNomComplet()
        ];
        return new JsonResponse($data, 200);
    
}


         /**
         * @Route("/compte/cptcompte", name="compte", methods={"GET"})
        */

        public function NbComptes(){

            $compte = $this->getDoctrine()->getRepository(Compte::class);
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
         * @Route("/compte/sommesolde", methods={"GET"})
        */

        public function sommeSolde(){

            $compte = $this->getDoctrine()->getRepository(Compte::class);
            $comptes = $compte->findAll();
            //  dd($partenaire); die;
            $somme=0;
            foreach ($comptes as  $value)
                {
                    $cpt=0;
                    if ($value) {
                        $cpt=$value->getSolde();
                    }
                    
                $somme=$somme+$cpt;
                }
                return $this->json( $somme);
            

        }
                

    

  
}


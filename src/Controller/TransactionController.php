<?php

namespace App\Controller;
use App\Entity\Compte;
use App\Entity\Part;
use App\Entity\Tarif;
use App\Entity\Affectaion;
use App\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony \ Component \ Security \ Core \ Exception \ AccessDeniedException ;
use Twilio\Rest\Client;
// require '../../vendor/autoload.php';

use \Osms\Osms;
/**
 * @Route("/api")
 */
class TransactionController extends AbstractController

{

                public function Frais($montant)
                {
                    $frai = $this->getDoctrine()->getRepository(Tarif::class);
                    $all = $frai->findAll();
                //    var_dump($all); die;
                    foreach($all as $val)
                    {
                        
                        if($val->getBonInf() <= $montant && $val->getBornSup()>= $montant)
                        {
                            return $val->getFrais(); 
                        }
                    }

                }

                public function Code($code)
                {
                    
                    $retrait = $this->getDoctrine()->getRepository(Transaction::class);
                    $rett = $retrait->findAll();
                //    var_dump($all); die;
                    foreach ($rett as  $value)
                    {
                        if ($code==$value->getCode())
                        { 
                        return  true;
                        }
                    }
                }
    /**
     * @Route("/transaction/depot", name="depot", methods={"POST"})
     */

    public function new(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {

        // $this-> denyAccessUnlessGranted(['ROLE_PARTENAIRE','ADMIN_PARTENAIRE','CAISSIER_PARTENAIRE']);
    
        $Transaction =$serializer->deserialize($request->getContent(), Transaction::class, 'json');
        // $frai = $this->getDoctrine()->getRepository(Tarif::class);
        //  $all = $frai->findAll();
         

       

      
        $errors = $validator->validate($Transaction);
        if(count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json'
            ]);
        }
        $gneneCode=rand (1, 100);
        // var_dump($gneneCode);die();
        $date=new \DateTime('now');
        $code=$gneneCode.date_format($date, 'YmdHi');
        //user connecte
        $users = $this->getUser();

        
        //appele fonction frais

        $fr = $this->Frais($Transaction->getMontant());
          
      
          //calculer les parts
        $part = $this->getDoctrine()->getRepository(Part::class);
          $partTaxe = $part->findAll()[0];
    
          $compte = $this->getDoctrine()->getRepository(Compte::class);
          $comptes = $compte->findAll();


        $partEta=$fr*$partTaxe->getEtat();
        $partSys=$fr*$partTaxe->getSysteme();
        $partDep=$fr*$partTaxe->getDep();
        $partRet=$fr*$partTaxe->getRet();

        //recuperer le compte affecte a  l'utilisateur connecte 

        $affectet = $this->getDoctrine()->getRepository(Affectaion::class);
         $all = $affectet->findAll();

        
         foreach($all as $valeur)
         {
            
             if ($valeur->getUsers()->getId()===$users->getId() && $valeur->getUsers()->getIsActive()==true)
                {
                    if ($valeur->getDateFin() < $date)
                    {
                        $data = [
                            'status' => 500,
                            'message' =>'Desole ta date d\'affectation est terminée depuis le : '.
                            date_format($valeur->getDateFin(), 'Y-m-d H:i:s')
                        ];
                
                        return new JsonResponse($data, 200);   
                    } 
                       
                    if ($valeur->getComptes()->getSolde()<=$Transaction->getMontant()) {
                        $data = [
                            'status' => 500,
                            'message' =>('L\'etat de votre Compte ne vous permet d\'effectue cette transaction votre solde est: '.
                            $valeur->getComptes()->getSolde().' et le montant de la transaction est '.
                            $Transaction->getMontant().' Desolé !!!!')
                        ];
                
                        return new JsonResponse($data, 200);
            
                    }

                    
                   
                //   var_dump($valeur->getComptes()->getSolde());die();


                //recuperer le compte de l'utilisateur
                
            //   $compteAfec=$valeur->getComptes();

               // var_dump($partEta);die();
                //  var_dump($partSys);die();
                //  var_dump($partDep);die();
                //  var_dump($partRet);die();


                $Transaction->setDatetransaction($date);
                // $Transaction->setUserTransaction($users);//le user qui a fais le depot
                $Transaction->setTarifs($fr);
                // dd($fr);
                $Transaction->setStatus(false);
                $Transaction->setCode($code);
                $Transaction->setPartEtat($partEta);
                $Transaction->setPartSysteme($partSys);
                $Transaction->setPartDep($partDep);
                $Transaction->setPartRet($partRet);
                $Transaction->setComptesDep($Transaction->getComptesDep());//le compte ou on a fais le depot
                $Transaction->setDepotUse($users);//le user ou on a fais le depot
                $entityManager->persist($Transaction);
                $entityManager->flush();

                $mypart=$Transaction->getTarifs()-$Transaction->getPartDep();

                //mis a jour Du Compte

                $nouv=($Transaction->getMontant() + $mypart);
                $nouveau = ($Transaction->getComptesDep()->getSolde() - $nouv);
                $repCompt=$Transaction->getComptesDep()->setSolde($nouveau);
                $entityManager->persist($repCompt);
                $entityManager->flush();

                $data = [
                    'status' => 200,
                    'message' => 'Vous Avez Efeectue Une Operation de Depot  De '.$Transaction->getMontant().' Frais: '.$Transaction->getTarifs().
                    ' Au Numero '.$Transaction->getTelrep().' Voici Le Code De La Transaction '.$Transaction->getCode().' Le : '
                    .date_format($Transaction->getDatetransaction(), 'Y-m-d H:i:s').
                    ' Votre Nouvou Solde pour le compte Numero '.$repCompt->getNumero().' Est : '.$repCompt->getSolde()
                ];
                        return new JsonResponse($data, 200);


                }
            }
                if($users->getRoles()===['ROLE_PARTENAIRE'])
                {
                    foreach ($comptes as $key) {
                        if ($users->getPartenaire()  === $key->getPartenaire()) {

                        //    $comptePartenaire=$key;

                     
                 
         
                // var_dump($partEta);die();
                //  var_dump($partSys);die();
                //  var_dump($partDep);die();
                //  var_dump($partRet);die();


                $Transaction->setDatetransaction($date);
                // $Transaction->setUserTransaction($users);//le user qui a fais le depot
                $Transaction->setTarifs($fr);
                // dd($fr);
                $Transaction->setStatus(false);
                $Transaction->setCode($code);
                $Transaction->setPartEtat($partEta);
                $Transaction->setPartSysteme($partSys);
                $Transaction->setPartDep($partDep);
                $Transaction->setPartRet($partRet);
                $Transaction->setComptesDep($Transaction->getComptesDep());//le compte ou on a fais le depot
                $Transaction->setDepotUse($users);//le user ou on a fais le depot
                $entityManager->persist($Transaction);
                $entityManager->flush();
                
                $mypart=$Transaction->getTarifs()-$Transaction->getPartDep();

                // dd($mypart);
                //mis a jour Du Compte

                $nouv=($Transaction->getMontant() + $mypart);
                // dd($nouv);
                $nouveau = ($Transaction->getComptesDep()->getSolde() - $nouv);
                $repCompt=$Transaction->getComptesDep()->setSolde($nouveau);
                $entityManager->persist($repCompt);
                $entityManager->flush();

                $data = [
                    'status' => 200,
                    'message' => 'Vous Avez Efeectue Une Operation de Depot  De '.$Transaction->getMontant().' Frais: '.$Transaction->getTarifs().
                    ' Au Numero '.$Transaction->getTelrep().' Voici Le Code De La Transaction '.$Transaction->getCode().' Le : '
                    .date_format($Transaction->getDatetransaction(), 'Y-m-d H:i:s').
                    ' Votre Nouvou Solde pour le compte Numero '.$repCompt->getNumero().' Est : '.$repCompt->getSolde()
                ];
                        

                        return new JsonResponse($data, 200);
                 }
                }
            }
                   
            
            $data = [
                'status' => 201,
                'message' => 'On Vous n\'a pas encore affecte de compte'
            ];
                    return new JsonResponse($data, 201);
            
        }

        

        

    /**
     *@Route("/transaction/retait", name="retrait", methods={"POST"})
     */

    public function retait(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {

        // $this-> denyAccessUnlessGranted(['ROLE_SUP_ADMIN','ROLE_ADMIN_PARTENAIRE','ROLE_CAISSIER_PARTENAIRE']);
    
        
  
        $users = $this->getUser();

        
        $cccc =$serializer->deserialize($request->getContent(), Transaction::class, 'json');
          $Transaction=json_decode($request->getContent());
        $errors = $validator->validate($Transaction);
        if(count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json'
            ]);
        }
       

        $compte = $this->getDoctrine()->getRepository(Compte::class);
          $comptes = $compte->findAll();

        $affectet = $this->getDoctrine()->getRepository(Affectaion::class);
        $allAffect = $affectet->findAll();
        

        foreach($allAffect as $valeurs)
        {
            if ($valeurs->getUsers()->getId()==$users->getId() && $users->getIsActive()==true)
              {
                  //recuperer le copte de celui qui est connecte

                        // $compteAfec=$valeurs->getComptes();

                  if ($this->Code($Transaction->code)==true)
                    {
                     //recuperer la ligne de la transaction dont le code a ete fourni
   
                       $retrait = $this->getDoctrine()->getRepository(Transaction::class);
                        $rett = $retrait->findOneByCode($Transaction->code);
                       
                           
                        if ($cccc->getCompteRetraits()->getSolde() < $rett->getMontant())
                         {
                                $data = [
                                    'status' => 500,
                                    'message' =>('L\'etat de votre Compte ne vous permet d\'effectue cette transaction')
                                ];
                        
                                return new JsonResponse($data, 200);
                           }
                        if ($rett->getStatus()==true) 
                          {
                                $data = [
                                    'status' => 500,
                                    'message' =>('Cette Tansaction a ete retire!!')
                                ];
                        
                                return new JsonResponse($data, 200);
                          }
                           
                        $rett->setDateRet(new \DateTime());
                        $rett->setStatus(true);
                        $rett->setPiece($Transaction->piece);
                        $rett->setCompteRetraits($cccc->getCompteRetraits());
                        $rett->setRetaitUse($users);
                        $entityManager->persist($rett);
                        $entityManager->flush();
                    
                        //mise a jour le solde du compte
                        $nouv=($rett->getMontant() + $rett->getPartRet());
                        $nouveau = ($rett->getCompteRetraits()->getSolde() + $nouv);
                        $repCompt=$rett->getCompteRetraits()->setSolde($nouveau);
                        $entityManager->persist($repCompt);
                        $entityManager->flush();

                  
                            $data = [
                            'status' => 200,
                            'message' => 'Vous Venez De Retirer '.$rett->getMontant().' De La Part De '.$rett->getNomdep().
                            ' Dans Notre Distributeur '.$users->getUsername().' Merci d avoir Utiliser YoneMa '.
                            date_format($rett->getDateRet(), 'Y-m-d H:i:s').' Nouvou solde est: '.$rett->getCompteRetraits()->getSolde()
                             ];
                                return new JsonResponse($data, 200);

                    }
                        $data = [
                            'status' => 500,
                            'message' => 'Desole cet Code est Ivalide!!'
                            ];
                              return new JsonResponse($data, 500);
                        

                }
            }
                if($users->getRoles()===['ROLE_PARTENAIRE'])
                {

                    foreach ($comptes as $key) 
                    {

                        if ($users->getPartenaire()  === $key->getPartenaire())
                         {

                        if ($this->Code($Transaction->code)==true)
                        {
                        //recuperer la ligne de la transaction dont le code a ete fourni
        
                            $retrait = $this->getDoctrine()->getRepository(Transaction::class);
                            $rett = $retrait->findOneByCode($Transaction->code);
                            
                                
                            if ($cccc->getCompteRetraits()->getSolde() < $rett->getMontant())
                            {
                                    $data = [
                                        'status' => 500,
                                        'message' =>('L\'etat de votre Compte ne vous permet d\'effectue cette transaction')
                                    ];
                            
                                    return new JsonResponse($data, 200);
                            }
                            if ($rett->getStatus()==true) 
                                {
                                    $data = [
                                        'status' => 500,
                                        'message' =>('Tansaction  deja  retiré!!')
                                    ];
                            
                                    return new JsonResponse($data, 200);
                                }
                                
                            $rett->setDateRet(new \DateTime());
                            $rett->setStatus(true);
                            $rett->setPiece($Transaction->piece);
                            $rett->setCompteRetraits($cccc->getCompteRetraits());
                            $rett->setRetaitUse($users);
                            $entityManager->persist($rett);
                            $entityManager->flush();

                            // $mypart=$rett->getTarifs() / $rett->getRet();

                            //mise a jour le solde du compte
                            $nouv=($rett->getMontant() + $rett->getPartRet());
                            // dd($nouv);
                            $nouveau = ($rett->getCompteRetraits()->getSolde() + $nouv);
                            $repCompt=$rett->getCompteRetraits()->setSolde($nouveau);
                            $entityManager->persist($repCompt);
                            $entityManager->flush();

                        
                                $data = [
                                'status' => 200,
                                'message' => 'Vous Venez De Retirer '.$rett->getMontant().' De La Part De '.$rett->getNomdep().
                                ' Dans Notre Distributeur '.$users->getUsername().' Merci d avoir Utiliser YoneMa '.
                                date_format($rett->getDateRet(), 'Y-m-d H:i:s').' Nouvou solde est: '.$rett->getCompteRetraits()->getSolde()                                ];
                                    return new JsonResponse($data, 200);

                          }
                      $data = [
                          'status' => 500,
                          'message' => 'Desole cet Code est Ivalide!!'
                          ];
                            return new JsonResponse($data, 500);

                        }
                    }

                }
                  $data = [
                    'status' => 500,
                    'message' => 'On Vous n\'a pas encore affecte de compte'
                        ];
                            return new JsonResponse($data, 500);
                    
            
        
                  
     }

     /**
     *@Route("/transactionbyusers",  methods={"GET"})
     */

    public function getByUsers()
    {
        $users = $this->getUser();

        $transaction = $this->getDoctrine()->getRepository(Transaction::class);
        $all = $transaction->findAll();

        $data=[];
        $i=0;

        if ($users->getRoles() ===['ROLE_PARTENAIRE'])
       {
            foreach ($all as $key)
             {
                
                // dd($key->getDepotUse()->getUsername());
                if ($users->getPartenaire()===$key->getDepotUse()->getPartenaire()&&($key->getStatus()==null)) {
                    //   dd($key);
                     $data[$i]=$key;
                        $i++;         
                }
              
                
            }
        }
        elseif($users->getRoles() ===['ROLE_CAISSIER_PARTENAIRE'])
        {
            foreach ($all as $key)
            {
               
               // dd($key->getDepotUse()->getUsername());
               if ($users===$key->getDepotUse()
              &&($key->getStatus()==null)){
                   //   dd($key);
                    $data[$i]=$key;
                       $i++;         
               }
             
               
           }
        
       }
       elseif($users->getRoles() ===['ROLE_ADMIN_PARTENAIRE'])
       {
           foreach ($all as $key)
           {
              
              // dd($key->getDepotUse()->getUsername());
              if ($users===$key->getDepotUse()
            &&($key->getStatus()==null) ){
                  //   dd($key);
                   $data[$i]=$key;
                      $i++;         
              }
            
              
          }
       
      }
               
               return $this->json($data, 200);
        
    }



     /**
     *@Route("/transactionRetbyusers",  methods={"GET"})
     */

    public function getOpByUsers()
    {
        $users = $this->getUser();

        $transaction = $this->getDoctrine()->getRepository(Transaction::class);
        $all = $transaction->findAll();

        $data=[];
        $i=0;

        if ($users->getRoles() ===['ROLE_PARTENAIRE'])
       {
            foreach ($all as $key)
             {
                
                // dd($key->getDepotUse()->getUsername());
                if ($users===$key->getRetaitUse() &&($key->getStatus()==true)) {
                    //   dd($key);
                     $data[$i]=$key;
                        $i++;         
                }
              
                
            }
        }
        elseif($users->getRoles() ===['ROLE_CAISSIER_PARTENAIRE'])
        {
            foreach ($all as $key)
            {
               
               // dd($key->getDepotUse()->getUsername());
               if ($users===$key->getRetaitUse() &&($key->getStatus()==true)
               ){
                   //   dd($key);
                    $data[$i]=$key;
                       $i++;         
               }
             
               
           }
        
       }
       elseif($users->getRoles() ===['ROLE_ADMIN_PARTENAIRE'])
       {
           foreach ($all as $key)
           {
              
              // dd($key->getDepotUse()->getUsername());
              if ($users===$key->getRetaitUse() &&($key->getStatus()==true)
               ){
                  //   dd($key);
                   $data[$i]=$key;
                      $i++;         
              }
            
              
          }
       
      }
               
               return $this->json($data, 200);
        
    }
      
            /**
             * @Route("/listerComptesByTransaction", name="listecomptesTransaction", methods={"GET"})
            */
            public function getCompte()
            {
                
                $repo = $this->getDoctrine()->getRepository(Compte::class);
                $compte = $repo->findAll();

                $repoAffect = $this->getDoctrine()->getRepository(Affectaion::class);
                $affectaion = $repoAffect->findAll();
                
                
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
                }elseif($ucsercon->getRoles() ==  ["ROLE_ADMIN_PARTENAIRE"]|| $ucsercon->getRoles() ==  ["ROLE_CAISSIER_PARTENAIRE"])
                        {   
                            foreach ($affectaion as $key) {
                              if ($key->getUsers()===$ucsercon) {

                                 $data[$i]=$key->getComptes();
                                  $i++;
                              }
                            }
                 
                         
             
                        }
                        return $this->json($data, 200);
           
            }
  }

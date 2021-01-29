<?php

namespace App\DataFixtures;
// use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Role;
use App\Entity\User;
use App\DataFixtures\AppFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker;

class AppFixtures extends Fixture 
{
    private $encoder;
 
    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {  
        $faker = Faker\Factory::create('fr_FR');

        $supad=new Role();
        $supad->setLibelle("SUP_ADMIN");
        $manager->persist($supad);

        $ad=new Role();
        $ad->setLibelle("ADMIN");
        $manager->persist($ad);

        $cais=new Role();
        $cais->setLibelle("CAISSIER");
        $manager->persist($cais);

        $par=new Role();
        $par->setLibelle("PARTENAIRE");
        $manager->persist($par);

        $adminpar=new Role();
        $adminpar->setLibelle("ADMIN_PARTENAIRE");
        $manager->persist($adminpar);

        $caissierpar=new Role();
        $caissierpar->setLibelle("CAISSIER_PARTENAIRE");
        $manager->persist($caissierpar);



        $manager->flush();
     

        $this->addReference('role_sup_admin',$supad);
        $this->addReference('role_admin',$ad);
        $this->addReference('role_caissier',$cais);
        $this->addReference('role_partenaire',$par);
        $this->addReference('role_admin_partenaire',$adminpar);
        $this->addReference('role_caissier_partenaire',$caissierpar);


        $roleSupAd=$this->getReference('role_sup_admin');
        $roleAd=$this->getReference('role_admin');
        $roleCais=$this->getReference('role_caissier');
        $rolePar=$this->getReference('role_partenaire');
        $roleAdPar=$this->getReference('role_admin_partenaire');
        $roleCaisPar=$this->getReference('role_caissier_partenaire');



        $user = new User();
        $user->setNomComplet('Lamine Mbaye');
        $user->setUsername('lamine@gmail.com');
        $user->setPassword($this->encoder->encodePassword($user, "passer"));
        $user->setRoles(["ROLE_".$supad->getLibelle()]);
        $user->setProfil($supad);
        // $user->setPartenaire(null);

        $user->setIsActive(true);


        $manager->persist($user);
        $manager->flush();
    }
   
}
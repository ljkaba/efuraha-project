<?php

namespace App\DataFixtures;


namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture 
{
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
   


    public function load(ObjectManager $manager)
    {
        
        $faker = Faker\Factory::create('fr_FR');


        $user = Array();
           for ($i = 0; $i < 40; $i++) {
            $user[$i] = new User();
            $user[$i]->setEmail($faker->email);
            $user[$i]->setLastName($faker->name);
            $user[$i]->setDescription($faker->sentence($nbWords = 6, $variableNbWords = true));
           
            if($i=== 1)
                $user[$i]->setRoles(["ROLE_ADMIN"]);
            else
            $user[$i]->setRoles(["ROLE_USER"]);
           
          
            $user[$i]->setPassword($this->passwordEncoder->encodePassword($user[$i],"wick"));
          
           
            $manager->persist($user[$i]);

        $manager->flush();
    }
}
}

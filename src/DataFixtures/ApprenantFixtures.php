<?php

namespace App\DataFixtures;

use App\Entity\Apprenant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApprenantFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR') ;

        $user = new Apprenant() ;
        $user ->setEmail ($faker->email);
        $password = $this->encoder->encodePassword($user, 'pass_1234') ;
        $user ->setFirtname($faker->name);
        $user ->setLastname($faker->lastName);
        // $user ->setPhone(223666);
        $user ->setUsername($faker->userName);
        // $user ->setAddress($faker->address);
         $user->setArchivage(false) ;
        $user->setPassword($password) ;
        $user->setProfil($this->getReference(ProfilFixtures::APPRENANT_REFERENCE));
        $manager->persist($user);

        $manager->flush();
    }
    public function getDependencies()
    {
        return array(
            ProfilFixtures::class,
        );
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\Nomade;
use App\Entity\Proprietaire;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        $admin = new Admin();
        $admin->setEmail('christian.boungou@gmail.com');
        $admin->setNom('BOUNGOU');
        $admin->setPrenom('Christian');
        $password = $this->encoder->encodePassword($admin, '123456');
        $admin->setPassword($password);
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        for ($i=0 ; $i < 10; $i++){
            $nomade = new Nomade();
            $nomade->setEmail('nomade' . $i . '@gmail.com');
            $nomade->setNom($faker->lastName);
            $nomade->setPrenom($faker->firstName);
            $nomade->setAdresse($faker->streetAddress);
            $nomade->setCp('91000');
            $nomade->setDateCreationCompte($faker->dateTime);
            $nomade->setVille($faker->city);
            $nomade->setTelephone('0102030405');
            $nomade->setTypeSejour('Autre');
            $nomade->setSexe(random_int(1,2));
            $nomade->setBudget(random_int(200,1000));
            $nomade->setStatut('Autre');
            $nomade->setDateNaissance($faker->dateTime);
            $nomade->setIsConfirmed(1);
            $password = $this->encoder->encodePassword($nomade, 'nomade' . $i);
            $nomade->setPassword($password);
            $manager->persist($nomade);
        }

        for ($i=0 ; $i < 10; $i++){
            $proprietaire = new Proprietaire();
            $proprietaire->setEmail('proprio' . $i . '@gmail.com');
            $proprietaire->setRaisonSocial($faker->lastName);
            $proprietaire->setAdresse($faker->streetAddress);
            $proprietaire->setCp(random_int(12000,99000));
            $proprietaire->setDateCreationCompte($faker->dateTime);
            $proprietaire->setVille($faker->city);
            $proprietaire->setTelephone('0102030405');
            $proprietaire->setStatut('Autre');
            $proprietaire->setIsConfirmed(1);
            $proprietaire->setRefus(1);
            $password = $this->encoder->encodePassword($proprietaire, 'proprio' . $i);
            $proprietaire->setPassword($password);
            $proprietaire->setRoles(['ROLE_PROPRIO']);
            $manager->persist($proprietaire);
        }

        $manager->flush();
    }
}

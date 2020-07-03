<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\Annonce;
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
            $nomade->setCp($faker->numberBetween(12,99) . "000");
            $nomade->setDateCreationCompte($faker->dateTime);
            $nomade->setVille($faker->city);
            $nomade->setTelephone('01' . $faker->numberBetween(10000000,99999999));
            $nomade->setTypeSejour('Autre');
            $nomade->setSexe($faker->randomElement(array('Féminin','Masculin')));
            $nomade->setBudget($faker->numberBetween(200,1000));
            $nomade->setStatut('Autre');
            $nomade->setPresentation($faker->text(100));
            $nomade->setDateNaissance($faker->dateTime);
            $nomade->setIsConfirmed(1);
            $password = $this->encoder->encodePassword($nomade, 'nomade' . $i);
            $nomade->setPassword($password);
            $manager->persist($nomade);
        }

        for ($k=0 ; $k < 10; $k++){
            $proprietaire = new Proprietaire();
            $proprietaire->setEmail('proprio' . $k . '@gmail.com');
            $proprietaire->setRaisonSocial($faker->lastName);
            $proprietaire->setAdresse($faker->streetAddress);
            $proprietaire->setCp($faker->numberBetween(12,99) . "000");
            $proprietaire->setDateCreationCompte($faker->dateTime);
            $proprietaire->setVille($faker->city);
            $proprietaire->setTelephone('0102030405');
            $proprietaire->setStatut('Autre');
            $proprietaire->setIsConfirmed(1);
            $proprietaire->setRefus(1);
            $password = $this->encoder->encodePassword($proprietaire, 'proprio' . $k);
            $proprietaire->setPassword($password);
            $proprietaire->setRoles(['ROLE_PROPRIO']);
            $manager->persist($proprietaire);

            for ($j=0 ; $j < 3; $j++){
                $annonce = new Annonce();
                $annonce->setTitre($faker->words(3,true));
                $annonce->setTypeLogement('Autre');
                $annonce->setAdresse($faker->streetAddress);
                $annonce->setCp($faker->numberBetween(12,99) . "000");
                $annonce->setVille($faker->city);
                $annonce->setNombreMaxResidents($faker->numberBetween(1,4));
                $annonce->setDateDisponible($faker->dateTimeBetween('0 years','+1 year'));
                $annonce->setSuperficie($faker->numberBetween(5,50));
                $annonce->setTarif($faker->numberBetween(250,999));
                $annonce->setDescription($faker->text(500));
                $annonce->setProprio($proprietaire);
                $annonce->setPublicationAuth(1);
                $manager->persist($annonce);
            }
        }




        $manager->flush();
    }
}

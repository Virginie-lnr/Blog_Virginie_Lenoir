<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
            $article = new Article();

            $article
                ->setTitre('Au sommet «Ambition climat», des engagements trop frileux')
                ->setContenu('Organisée pour rehausser les engagements climatiques mondiaux, cinq ans après la signature de l’Accord de Paris, la réunion de plus de 70 chefs d’États n’a pas tenu ses promesses.')
                ->setDateCreation(new \DateTime('now'))
            ;
            $manager->persist($article);

            $manager->flush();
    }
}

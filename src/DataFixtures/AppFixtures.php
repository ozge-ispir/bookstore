<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Category;
use App\Entity\Author;
use App\Entity\User;
use App\Entity\Book;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        for($i=1; $i<6; $i++){

            $category = new Category();
            $category->setName('CATEGORIE '.$i);

            $manager->persist($category);

            $author = new Author();
            $author->setLastName('NOM'.$i)
                   ->setFirstName('PRENOM'.$i);

            $manager->persist($author);

            $user = new user();
            $user->setEmail("email".$i."@symfony.com")
                ->setPassword("password".$i)
                ->setAddress("adresse".$i)
                ->setZipCode("CP".$i)
                ->setCity("VILLE".$i)
                ->setLastName("NOMUSER".$i)
                ->setFirstName("PRENOMUSER".$i);

            $manager->persist($user);

            for($j=1; $j<6; $j++){
                $book = new Book();
                $book->setTitle("titre ".$i." ".$j)
                        ->setIsbn("ISBN".$i." ".$j)
                        ->addAuthor($author)
                        ->addCategory($category)
                        ->setPrice(mt_rand(10, 200));
                
                $manager->persist($book);
            }


        }


        $manager->flush();
    }
}

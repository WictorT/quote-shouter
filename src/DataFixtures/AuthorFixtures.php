<?php

namespace App\DataFixtures;

use App\Entity\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AuthorFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $author = (new Author)
            ->setName('Steve Jobs')
            ->setSlug('steve-jobs');

        $manager->persist($author);
        $manager->flush();
    }
}

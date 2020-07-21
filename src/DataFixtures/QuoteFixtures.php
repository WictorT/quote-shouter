<?php

namespace App\DataFixtures;

use App\Entity\Quote;
use App\Repository\AuthorRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class QuoteFixtures extends Fixture implements DependentFixtureInterface
{
    /** @var AuthorRepository */
    private $authorRepository;

    public function __construct(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    public function getDependencies()
    {
        return array(
            AuthorFixtures::class,
        );
    }

    public function load(ObjectManager $manager)
    {
        $authorId = $this->authorRepository
            ->findOneBy(['slug' => 'steve-jobs'])
            ->getId();

        $manager->persist(
            (new Quote)
                ->setOriginal('The only way to do great work is to love what you do.')
                ->setAuthorId($authorId)
        );

        $manager->persist(
            (new Quote)
                ->setOriginal('Your time is limited, so don’t waste it living someone else’s life.')
                ->setAuthorId($authorId)
        );

        $manager->flush();
    }
}

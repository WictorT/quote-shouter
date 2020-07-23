<?php


namespace App\Handler;


use App\Entity\Author;
use App\Repository\AuthorRepository;
use App\Service\TheySaidSoClient;
use Doctrine\ORM\EntityManagerInterface;
use stdClass;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthorHandler
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var Author */
    private $authorRepository;

    /** @var TheySaidSoClient */
    private $theySaidSoClient;

    public function __construct(
        EntityManagerInterface $entityManager,
        AuthorRepository $authorRepository,
        TheySaidSoClient $theySaidSoClient
    ) {
        $this->entityManager = $entityManager;
        $this->authorRepository = $authorRepository;
        $this->theySaidSoClient = $theySaidSoClient;
    }

    public function getAuthorBySlug(string $slug)
    {
        $authorFromDB = $this->authorRepository->findOneBy(['slug' => $slug]);
        if ($authorFromDB) {
            return $authorFromDB;
        }

        $authorsFromAPI = $this->theySaidSoClient->searchAuthors($slug);
        if (!$authorsFromAPI) {
            throw new NotFoundHttpException('Author not found.');
        }

        $newAuthor = $this->getAuthorFromResponse($authorsFromAPI, $slug);
        if (!$newAuthor) {
            throw new NotFoundHttpException('Author not found.');
        }

        return $newAuthor;
    }

    private function getAuthorFromResponse(stdClass $response, string $slug): ?Author
    {
        $authorsFromAPI = $response->contents->authors;
        if (!$authorsFromAPI) {
            return null;
        }

        $newAuthor = null;
        foreach ($authorsFromAPI as $authorFromAPI) {
            if ($authorFromAPI->slug === $slug) {
                $newAuthor = (new Author)
                    ->setName($authorFromAPI->name)
                    ->setSlug($authorFromAPI->slug);

                break;
            }
        }

        if ($newAuthor) {
            $this->entityManager->persist($newAuthor);
            $this->entityManager->flush();
        }

        return $newAuthor;
    }
}

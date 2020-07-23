<?php


namespace App\Handler;


use App\Entity\Author;
use App\Entity\Quote;
use App\Repository\QuoteRepository;
use App\Service\TheySaidSoClient;
use Doctrine\ORM\EntityManagerInterface;
use stdClass;

class QuoteHandler
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var Author */
    private $quoteRepository;

    /** @var TheySaidSoClient */
    private $theySaidSoClient;

    public function __construct(
        EntityManagerInterface $entityManager,
        QuoteRepository $quoteRepository,
        TheySaidSoClient $theySaidSoClient
    ) {
        $this->entityManager = $entityManager;
        $this->quoteRepository = $quoteRepository;
        $this->theySaidSoClient = $theySaidSoClient;
    }

    public function getAuthorQuotes(Author $author, int $limit)
    {
        $quotesFromDB = $this->quoteRepository->getQuotesForAuthor($author, $limit);
        if ($quotesFromDB) {
            return $quotesFromDB;
        }

        $quotesFromAPI = $this->theySaidSoClient->getQuotesForAuthor($author);

        if ($quotesFromAPI) {
            return $this->getQuotesFromResponse($quotesFromAPI, $author);
        }

        return [];
    }

    private function getQuotesFromResponse(stdClass $response, Author $author): array
    {
        $quotesFromAPI = $response->contents->quotes;
        if (!$quotesFromAPI) {
            return [];
        }

        $newQuotes = [];
        foreach ($quotesFromAPI as $quote) {
            $newQuote = (new Quote)
                ->setAuthor($author)
                ->setOriginal($quote->quote);
            $newQuotes[] = $newQuote;

            $this->entityManager->persist($newQuote);
        }

        if ($newQuotes) {
            $this->entityManager->flush();
        }

        return $newQuotes;
    }
}

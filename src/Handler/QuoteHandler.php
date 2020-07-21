<?php


namespace App\Handler;


use App\Entity\Author;
use App\Repository\QuoteRepository;

class QuoteHandler
{
    /** @var QuoteRepository */
    private $quoteRepository;

    public function __construct(QuoteRepository $quoteRepository)
    {
        $this->quoteRepository = $quoteRepository;
    }

    public function getAuthorQuotes(Author $author, int $limit)
    {
        $quotes = $this->quoteRepository->getQuotesForAuthor($author, $limit);
        if (!$quotes) {

        }

        return $quotes;
    }
}
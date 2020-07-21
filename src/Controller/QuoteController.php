<?php

namespace App\Controller;

use App\Repository\AuthorRepository;
use App\Repository\QuoteRepository;
use App\Transformer\QuoteTransformer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class QuoteController extends AbstractController
{
    /** @var AuthorRepository */
    private $authorRepository;

    /** @var QuoteRepository */
    private $quoteRepository;

    public function __construct(AuthorRepository $authorRepository, QuoteRepository $quoteRepository)
    {
        $this->authorRepository = $authorRepository;
        $this->quoteRepository = $quoteRepository;
    }


    /**
     * @Route("/shout/{author}", name="api.get.shout-quote")
     * @param string $author
     * @return JsonResponse
     */
    public function shout(string $author): JsonResponse
    {
        $author = $this->authorRepository->findOneBy(['slug' => $author]);
        if (!$author) {
            throw new NotFoundHttpException('Author not found.');
        }

        return $this->json(
            (new QuoteTransformer)->transformMultiple($author->getQuotes()->toArray())
        );
    }
}

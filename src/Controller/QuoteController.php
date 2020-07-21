<?php

namespace App\Controller;

use App\Handler\QuoteHandler;
use App\Repository\AuthorRepository;
use App\Transformer\QuoteTransformer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class QuoteController extends AbstractController
{
    /** @var QuoteHandler */
    private $quoteHandler;

    /** @var AuthorRepository */
    private $authorRepository;

    public function __construct(AuthorRepository $authorRepository, QuoteHandler $quoteHandler)
    {
        $this->quoteHandler = $quoteHandler;
        $this->authorRepository = $authorRepository;
    }

    /**
     * @Route(methods={"GET"}, path="/shout/{author}", name="api.get.shout-quote")
     *
     * @param string $author
     * @return JsonResponse
     */
    public function shout(string $author, Request $request): JsonResponse
    {
        $limit = $request->query->get('limit', 10);
        if (!preg_match('/^[1-9]0?$/', $limit)) {
            throw new BadRequestHttpException('Limit should be a number from 1 to 10.');
        }

        $author = $this->authorRepository->findOneBy(['slug' => $author]);
        if (!$author) {
            throw new NotFoundHttpException('Author not found.');
        }

        $quotes = $this->quoteHandler->getAuthorQuotes($author, $limit);

        return $this->json(
            (new QuoteTransformer)->transformMultiple($quotes)
        );
    }
}

<?php

namespace App\Controller;

use App\Handler\AuthorHandler;
use App\Handler\QuoteHandler;
use App\Transformer\QuoteTransformer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class QuoteController extends AbstractController
{
    /** @var AuthorHandler */
    private $authorHandler;

    /** @var QuoteHandler */
    private $quoteHandler;

    public function __construct(QuoteHandler $quoteHandler, AuthorHandler $authorHandler)
    {
        $this->authorHandler = $authorHandler;
        $this->quoteHandler = $quoteHandler;
    }

    /**
     * @Route(methods={"GET"}, path="/shout/{authorSlug}", name="api.get.shout-quote", requirements={"authorSlug"="[a-z]+-[a-z]+"})
     *
     * @param string $authorSlug
     * @return JsonResponse
     */
    public function shout(string $authorSlug, Request $request): JsonResponse
    {
        $limit = $request->query->get('limit', 10);
        if (!preg_match('/^[1-9]0?$/', $limit)) {
            throw new BadRequestHttpException('Limit should be a number from 1 to 10.');
        }

        $author = $this->authorHandler->getAuthorBySlug($authorSlug);

        $quotes = $this->quoteHandler->getAuthorQuotes($author, $limit);

        return $this->json(
            (new QuoteTransformer)->transformMultiple($quotes)
        );
    }
}

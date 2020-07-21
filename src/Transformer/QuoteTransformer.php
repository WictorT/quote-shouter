<?php


namespace App\Transformer;


use App\Entity\Quote;

class QuoteTransformer extends BaseTransformer
{
    /**
     * @param Quote $entity
     */
    public function transform($entity)
    {
        // trim spaces
        $quote = trim($entity->getOriginal());
        // remove symbols at the end
        $quote = rtrim($quote, '.!');
        // uppercase
        $quote = strtoupper($quote);
        // add `!` at the end
        $quote = $quote . '!';

        return $quote;
    }
}
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class QuoteController extends AbstractController
{
    /**
     * @Route("/shout/{author}", name="api.get.shout-quote")
     */
    public function shout()
    {
        return $this->json([
            "THE ONLY WAY TO DO GREAT WORK IS TO LOVE WHAT YOU DO!",
            "YOUR TIME IS LIMITED, SO DON’T WASTE IT LIVING SOMEONE ELSE’S LIFE!"
        ]);
    }
}

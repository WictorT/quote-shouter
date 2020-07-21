<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class QuoteControllerTest extends WebTestCase
{
    public function testShout()
    {
        $client = static::createClient();

        $client->request('GET', '/shout/steve-jobs');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals([
            "THE ONLY WAY TO DO GREAT WORK IS TO LOVE WHAT YOU DO!",
            "YOUR TIME IS LIMITED, SO DON’T WASTE IT LIVING SOMEONE ELSE’S LIFE!"
        ],
            json_decode($client->getResponse()->getContent())
        );
    }
}

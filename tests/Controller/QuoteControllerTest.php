<?php

namespace App\Tests\Controller;

use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class QuoteControllerTest extends WebTestCase
{
    public function testShoutSuccess()
    {
        $client = static::createClient();

        $client->request('GET', '/shout/steve-jobs?limit=2');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals([
            "THE ONLY WAY TO DO GREAT WORK IS TO LOVE WHAT YOU DO!",
            "YOUR TIME IS LIMITED, SO DON’T WASTE IT LIVING SOMEONE ELSE’S LIFE!"
        ],
            json_decode($client->getResponse()->getContent())
        );
    }

    public function testShoutNotFound()
    {
        $client = static::createClient();
        $entityManager = static::$container->get('doctrine.orm.entity_manager');
        $authorRepository = static::$container->get(AuthorRepository::class);

        $author = $authorRepository->findOneBy(['slug' => 'victor-timoftii']);
        $author && $entityManager->remove($author);

        $client->request('GET', "/shout/victor-timoftii");

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider dataShoutLimit
     * @param $limit
     * @param int $status
     * @param int $count
     */
    public function testShoutLimit($limit, int $status, ?int $count)
    {
        $client = static::createClient();

        $client->request('GET', "/shout/steve-jobs?limit=$limit");

        $this->assertEquals($status, $client->getResponse()->getStatusCode());
        $count && $this->assertCount($count, json_decode($client->getResponse()->getContent()));
    }

    /**
     * @return array
     */
    public function dataShoutLimit() : array
    {
        return [
            'case 1: low valid extreme' => [
                'limit' => 1,
                'status' => 200,
                'count' => 1
            ],
            'case 2: high valid extreme' => [
                'limit' => 10,
                'status' => 200,
                'count' => 2
            ],
            'case 3: random valid number' => [
                'limit' => rand(1, 10),
                'status' => 200,
                'count' => 2
            ],
            'case 4: low invalid extreme' => [
                'limit' => 0,
                'status' => 400,
                'count' => null
            ],
            'case 5: high invalid extreme' => [
                'limit' => 11,
                'status' => 400,
                'count' => null
            ],
            'case 6: negative number' => [
                'limit' => -5,
                'status' => 400,
                'count' => null
            ],
            'case 7: string' => [
                'limit' => 's',
                'status' => 400,
                'count' => null
            ],
            'case 8: empty' => [
                'limit' => null,
                'status' => 400,
                'count' => null
            ],
        ];
    }
}

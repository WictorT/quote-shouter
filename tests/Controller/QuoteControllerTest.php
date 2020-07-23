<?php

namespace App\Tests\Controller;

use App\Repository\AuthorRepository;
use App\Service\TheySaidSoClient;
use Mockery;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use function json_decode;

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

    public function testShoutSuccessAdvanced()
    {
        $client = static::createClient();

        $this->removeAuthorBySlug('anita-allen');

        $clientMock = Mockery::mock(TheySaidSoClient::class)
            ->shouldReceive('searchAuthors')
            ->once()
            ->andReturn(json_decode("{\n\"success\":{\n\"total\":\"103\",\n\"range\":{\n\"start\":0,\n\"end\":1\n}\n},\n\"contents\":{\n\"authors\":[\n{\n\"name\":\"AnitaAllen\",\n\"slug\":\"anita-allen\",\n\"id\":\"9pb6sEdioJiNae4zvsqfRAeF\"\n}\n],\n\"matched_query\":\"anita-allen\"\n},\n\"baseurl\":\"https://theysaidso.com\",\n\"copyright\":{\n\"year\":2022,\n\"url\":\"https://theysaidso.com\"\n}\n}"))
            ->shouldReceive('getQuotesForAuthor')
            ->once()
            ->andReturn(json_decode("{\n\"success\":{\n\"total\":1\n},\n\"contents\":{\n\"quotes\":[\n{\n\"quote\":\"This speaks well of our kids to play hard and make their way to the finals, ... All the kids played with lots of desire, hustle and Badger spirit all day.\",\n\"length\":\"138\",\n\"author\":\"AnitaAllen\",\n\"permalink\":\"https://theysaidso.com/quote/anita-allen-we-played-even-after-falling-behind-we-started-slow-and-dripping-spr\",\n\"tags\":[],\n\"id\":\"pMkYo186fxW8dIYOMlcJ4weF\",\n\"language\":\"en\",\n\"background\":null\n}\n],\n\"requested_category\":null,\n\"requested_author\":\"AnitaAllen\"\n},\n\"baseurl\":\"https://theysaidso.com\",\n\"copyright\":{\n\"year\":2022,\n\"url\":\"https://theysaidso.com\"\n}\n}"))
            ->getMock();

        static::$container->set('App\Service\TheySaidSoClient', $clientMock);

        $client->request('GET', '/shout/anita-allen?limit=2');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals([
            "THIS SPEAKS WELL OF OUR KIDS TO PLAY HARD AND MAKE THEIR WAY TO THE FINALS, ... ALL THE KIDS PLAYED WITH LOTS OF DESIRE, HUSTLE AND BADGER SPIRIT ALL DAY!"
        ],
            json_decode($client->getResponse()->getContent())
        );

        // Should not call the api again.
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testShoutNotFound()
    {
        $client = static::createClient();

        $this->removeAuthorBySlug('victor-timoftii');

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

    protected function removeAuthorBySlug(string $slug): void
    {
        $entityManager = static::$container->get('doctrine.orm.entity_manager');
        $authorRepository = static::$container->get(AuthorRepository::class);

        $author = $authorRepository->findOneBy(['slug' => $slug]);
        $author && $entityManager->remove($author);
        $entityManager->flush();
    }
}

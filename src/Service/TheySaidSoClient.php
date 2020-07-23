<?php


namespace App\Service;


use App\Entity\Author;
use Symfony\Component\HttpFoundation\Request;
use function curl_init;
use function json_decode;
use function rawurlencode;

class TheySaidSoClient
{
    public function getQuotesForAuthor(Author $author)
    {
        return json_decode(
            $this->call(
                Request::METHOD_GET,
                '/quote/search?limit=10&author=' . rawurlencode($author->getName())
            )
        );

    }

    public function searchAuthors(string $slug)
    {
        return json_decode(
            $this->call(
                Request::METHOD_GET,
                '/quote/authors/search?query=' . $slug
            )
        );
    }

    private function call($method, $route, $data = false)
    {
        $curl = curl_init();
        $url = $_ENV['THEY_SAID_SO_CLIENT_API_URL'] . $route;

        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);

                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        $headers = [
            'Content-Type: application/json'
        ];

        $apiKey = $_ENV['THEY_SAID_SO_CLIENT_API_KEY'];
        if (!empty($apiKey)) {
            $headers[] = 'X-TheySaidSo-Api-Secret: ' . $apiKey;
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }
}

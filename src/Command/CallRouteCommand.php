<?php

namespace App\Command;

use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;
use function json_decode;
use function json_encode;
use const JSON_PRETTY_PRINT;
use const PHP_EOL;

class CallRouteCommand extends Command
{
    protected static $defaultName = 'app:call-route';

    /**
     * @var AbstractBrowser
     */
    private $client;

    public function setClient(AbstractBrowser $client)
    {
        $this->client = $client;
    }

    protected function configure()
    {
        $this
            ->setDescription('This command calls http routes, and returns the response.')
            ->addArgument('route', InputArgument::REQUIRED, 'Route to be called')
            ->addOption('method', 'm', InputOption::VALUE_OPTIONAL, 'Request method.', Request::METHOD_GET)
            ->addOption('format', 'f', InputOption::VALUE_OPTIONAL, 'Response format. json or php.', 'php');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->client->request(
            $input->getOption('method'),
            $input->getArgument('route')
        );

        $response = json_decode($this->client->getResponse()->getContent());

        $this->outputResponse($response, $input->getOption('format'));

        return 0;
    }

    /**
     * @param $response
     * @param string $format
     */
    protected function outputResponse($response, string $format): void
    {
        if ($format === 'php') {
            print_r($response);
        } else if ($format === 'json') {
            echo json_encode($response, JSON_PRETTY_PRINT);
        }

        echo PHP_EOL;
    }
}

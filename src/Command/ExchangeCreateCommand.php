<?php

namespace App\Command;

use function get_class;
use function json_decode;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;use App\Factory\ExchangeFactory;
use App\Model\Exchange;
use App\Repository\ExchangeRepository;

class ExchangeCreateCommand extends Command
{
    /**
     * @var ExchangeRepository
     */
    private $exchangeRepository;

    /**
     * @var Exchange
     */
    private $exchange;

    /**
     * DatabaseSeedCommand constructor.
     *
     * @param ExchangeRepository $exchangeRepository
     */
    public function __construct(ExchangeRepository $exchangeRepository)
    {
        $this->exchangeRepository = $exchangeRepository;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:exchange:create')
            ->setDescription('Create an exchange')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        /** @var \Symfony\Component\Console\Helper\QuestionHelper $helper */
        $helper = $this->getHelper('question');

        $questionName = new Question('Exchange name ? ');
        $name = $helper->ask($input, $output, $questionName);

        $config = $helper->ask($input, $output, new Question('Exchange Config ?', '[]'));
        $config = json_decode($config);

        $this->exchange = ExchangeFactory::create(trim($name), $config);
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->exchangeRepository->insert($this->exchange);
    }

}

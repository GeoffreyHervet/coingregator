<?php

namespace App\Command;

use App\Domain\Handler\Exchange\CreatePairHandler;
use App\Domain\Request\Exchange\CreatePairRequest;
use App\Repository\ExchangeRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class ExchangeAddPairCommand extends Command
{
    use WithExchangeTrait;

    /**
     * @var CreatePairHandler
     */
    private $handler;

    /**
     * @var string
     */
    private $symbol;

    /**
     * ExchangeInfoCommand constructor.
     *
     * @param ExchangeRepository $exchangeRepository
     * @param CreatePairHandler $handler
     */
    public function __construct(ExchangeRepository $exchangeRepository, CreatePairHandler $handler)
    {
        $this->exchangeRepository = $exchangeRepository;
        $this->handler = $handler;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:exchange:pair:add')
            ->setDescription('Add a pair to an exchange');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->askExchange($input, $output);

        $helper = $this->getHelper('question');
        $question = new Question('What is the symbol ? ');
        $this->symbol = trim($helper->ask($input, $output, $question));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $request = new CreatePairRequest();
        $request->exchangeId = $this->exchange->getId();
        $request->symbol = $this->symbol;

        $output->writeln(json_encode($this->handler->handle($request), JSON_PRETTY_PRINT));
    }

}

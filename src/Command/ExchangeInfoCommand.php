<?php

namespace App\Command;

use App\Domain\Handler\Exchange\GetExchangeHandler;
use App\Domain\Request\Exchange\GetExchangeRequest;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Repository\ExchangeRepository;

class ExchangeInfoCommand extends Command
{
    use WithExchangeTrait;

    /**
     * @var GetExchangeHandler
     */
    private $handler;

    /**
     * ExchangeInfoCommand constructor.
     *
     * @param ExchangeRepository $exchangeRepository
     * @param GetExchangeHandler $handler
     */
    public function __construct(ExchangeRepository $exchangeRepository, GetExchangeHandler $handler)
    {
        $this->exchangeRepository = $exchangeRepository;
        $this->handler = $handler;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:exchange:info')
            ->setDescription('Create an exchange')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->askExchange($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $request = new GetExchangeRequest();
        $request->id = $this->exchange->getId();

        $output->writeln(json_encode($this->handler->handle($request), JSON_PRETTY_PRINT));
    }

}

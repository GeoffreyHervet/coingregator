<?php

namespace App\Command;

use App\Domain\Handler\Exchange\GetExchangeHandler;
use App\Domain\Request\Exchange\GetExchangeRequest;
use function array_keys;
use function dump;
use function get_class;
use function json_decode;
use function json_encode;
use const JSON_PRETTY_PRINT;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;use App\Factory\ExchangeFactory;
use App\Model\Exchange;
use App\Repository\ExchangeRepository;
use Tightenco\Collect\Support\Collection;

class ExchangeInfoCommand extends Command
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
        $helper = $this->getHelper('question');
        $exchanges = $this->exchangeRepository->all();
        $question = new ChoiceQuestion(
            'Which exchange ?',
            $exchanges->map(function (Exchange $exchange): string {
                return $exchange;
            })->toArray()
        );
        $question->setErrorMessage('Exchange %s is invalid.');

        $exchangeName = $helper->ask($input, $output, $question);
        $this->exchange = $exchanges->first(function (Exchange $exchange) use ($exchangeName): bool {
            return $exchangeName == $exchange;
        });
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $request = new GetExchangeRequest();
        $request->id = $this->exchange->getId();

        $output->writeln(json_encode($this->handler->handle($request), JSON_PRETTY_PRINT));
    }

}

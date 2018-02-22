<?php

namespace App\Command;

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
     * @var ExchangeReposito`ry
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
            $exchanges->toArray()
        );
        $question->setErrorMessage('Exchange %s is invalid.');

        $exchangeName = $helper->ask($input, $output, $question);
        $this->exchange = $exchanges->first(function (Exchange $exchange) use ($exchangeName): bool {
            return $exchangeName == $exchange;
        });
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $exchangeArray = $this->exchange->toArray();
        $exchangeArray['config'] = json_encode($exchangeArray['config'], JSON_PRETTY_PRINT);

        $table = new Table($output);
        $table->setHeaders(array_keys($exchangeArray))
            ->addRow($exchangeArray)
            ->render();
    }

}

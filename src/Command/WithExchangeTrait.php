<?php

namespace App\Command;

use App\Model\Exchange;
use App\Repository\ExchangeRepository;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

trait WithExchangeTrait
{
    /**
     * @var Exchange
     */
    private $exchange;

    /**
     * @var ExchangeRepository
     */
    private $exchangeRepository;

    private function askExchange(InputInterface $input, OutputInterface $output)
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
}

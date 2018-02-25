<?php

namespace App\Command;

use App\Domain\Handler\Exchange\CreatePairHandler;
use App\Domain\Request\Exchange\CreatePairRequest;
use App\Model\Asset;
use App\Model\ExchangePair;
use App\Repository\AssetRepository;
use App\Repository\ExchangePairRepository;
use App\Repository\ExchangeRepository;
use const PHP_EOL;
use function sprintf;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Tightenco\Collect\Support\Collection;

class ExchangePairLinkMissingCommand extends Command
{
    use WithExchangeTrait;

    /**
     * @var Asset[]|Collection
     */
    private $assets;

    /**
     * @var ExchangePair[]|Collection
     */
    private $nonLinkedPairs;

    /**
     * @var AssetRepository
     */
    private $assetRepository;

    /**
     * @var ExchangePairRepository
     */
    private $exchangePairRepository;

    /**
     * ExchangeInfoCommand constructor.
     *
     * @param ExchangeRepository $exchangeRepository
     * @param CreatePairHandler $handler
     */
    public function __construct(
        ExchangeRepository $exchangeRepository,
        AssetRepository $assetRepository,
        ExchangePairRepository $exchangePairRepository
    )
    {
        $this->exchangeRepository = $exchangeRepository;
        $this->assetRepository = $assetRepository;
        $this->exchangePairRepository = $exchangePairRepository;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:exchange:link-missing')
            ->setDescription('Link non linked pair to coins');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->askExchange($input, $output);
        $this->assets = $this->assetRepository->all();
        $this->nonLinkedPairs = $this->exchangePairRepository
            ->byExchange($this->exchange)
            ->filter(function (ExchangePair $pair): bool {
                return $pair->getFirstAsset() === null || $pair->getSecondAsset() === null;
            });
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->nonLinkedPairs->each(function (ExchangePair $pair) use ($input, $output) {
            $this->populatePair($pair, $input, $output);
            $this->exchangePairRepository->update($pair);
        });
    }

    private function populatePair(ExchangePair $pair, InputInterface $input, OutputInterface $output): ExchangePair
    {
        $output->writeln(PHP_EOL);
        $output->writeln(sprintf('Pair to populate is <info>%s</info>.', $pair));

        return $pair
            ->setFirstAsset($this->askAsset('What is the first asset ? ', $input, $output))
            ->setSecondAsset($this->askAsset('What is the first asset ? ', $input, $output))
            ->setIsWatching($this->askWatching($input, $output));
    }

    private function askAsset(string $questionValue, InputInterface $input, OutputInterface $output): Asset
    {
        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion($questionValue, $this->assets->map(function(Asset $asset): string {
            return $asset;
        })->toArray());
        $question->setErrorMessage('Asset %s is invalid.');

        $assetName = $helper->ask($input, $output, $question);
        return $this->assets->first(function (Asset $asset) use ($assetName): bool {
            return $assetName == $asset;
        });
    }

    private function askWatching(InputInterface $input, OutputInterface $output): bool
    {
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Watch pair ? ', false);

        return $helper->ask($input, $output, $question);
    }
}

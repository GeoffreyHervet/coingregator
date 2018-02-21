<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tightenco\Collect\Support\Collection;
use App\Factory\CoinFactory;
use App\Model\Coin;
use App\Repository\CoinRepository;

class CoinsSeedCommand extends Command
{
    /**
     * @var CoinRepository
     */
    private $coinRepository;

    /**
     * DatabaseSeedCommand constructor.
     *
     * @param CoinRepository $coinRepository
     */
    public function __construct(CoinRepository $coinRepository)
    {
        $this->coinRepository = $coinRepository;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('coins:seed')
            ->setDescription('Seed database with top coins');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->createCoins();
    }

    private function createCoins()
    {
        Collection::make([
            CoinFactory::create('Bitcoin', 'bitcoin'),
            CoinFactory::create('Ethereum', 'ethereum'),
            CoinFactory::create('Ripple', 'ripple'),
            CoinFactory::create('Bitcoin Cash', 'bitcoin-cash'),
            CoinFactory::create('Litecoin', 'litecoin'),
            CoinFactory::create('Cardano', 'cardano'),
            CoinFactory::create('NEO', 'neo'),
            CoinFactory::create('Stellar', 'stellar'),
            CoinFactory::create('Dash', 'dash'),
            CoinFactory::create('IOTA', 'iota'),
            CoinFactory::create('Monero', 'monero'),
            CoinFactory::create('NEM', 'nem'),
            CoinFactory::create('Ehtereum Classic', 'ehtereum-classic'),
            CoinFactory::create('Lisk', 'list'),
        ])
        ->each(function (Coin $coin) {
            $this->coinRepository->insert($coin);
        });
    }
}

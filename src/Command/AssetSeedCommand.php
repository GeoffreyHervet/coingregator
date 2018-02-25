<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tightenco\Collect\Support\Collection;
use App\Factory\AssetFactory;
use App\Model\Asset;
use App\Repository\AssetRepository;

class AssetSeedCommand extends Command
{
    /**
     * @var AssetRepository
     */
    private $assetRepository;

    /**
     * DatabaseSeedCommand constructor.
     *
     * @param AssetRepository $assetRepository
     */
    public function __construct(AssetRepository $assetRepository)
    {
        $this->assetRepository = $assetRepository;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:assets:seed')
            ->setDescription('Seed database with top assets');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->createAssets();
    }

    private function createAssets()
    {
        Collection::make([
            AssetFactory::create('Bitcoin', 'bitcoin'),
            AssetFactory::create('Ethereum', 'ethereum'),
            AssetFactory::create('Ripple', 'ripple'),
            AssetFactory::create('Bitcoin Cash', 'bitcoin-cash'),
            AssetFactory::create('Litecoin', 'litecoin'),
            AssetFactory::create('Cardano', 'cardano'),
            AssetFactory::create('NEO', 'neo'),
            AssetFactory::create('Stellar', 'stellar'),
            AssetFactory::create('Dash', 'dash'),
            AssetFactory::create('IOTA', 'iota'),
            AssetFactory::create('Monero', 'monero'),
            AssetFactory::create('NEM', 'nem'),
            AssetFactory::create('Ethereum Classic', 'ethereum-classic'),
            AssetFactory::create('Lisk', 'lisk'),
        ])
        ->each(function (Asset $asset) {
            $this->assetRepository->insert($asset);
        });
    }
}

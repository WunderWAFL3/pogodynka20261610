<?php

namespace App\Command;

use App\Repository\PlaceRepository;
use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:location',
    description: 'Pobiera prognozę pogody dla lokalizacji na podstawie ID',
)]
class WeatherPlaceCommand extends Command
{
    public function __construct(
        private PlaceRepository $placeRepository,
        private WeatherUtil $weatherUtil,
    ) {
        parent::__construct();
    }
    protected function configure(): void
    {
        $this
            ->addArgument('id', InputArgument::REQUIRED, 'Location ID');
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $id = $input->getArgument('id');

        $place = $this->placeRepository->find($id);

        if (!$place) {
            $io->error(sprintf('Lokalizacja o ID %d nie została znaleziona.', $id));
            return Command::FAILURE;
        }
        $measurements = $this->weatherUtil->getWeatherForPlace($place);
        $io->writeln(sprintf('Location: %s', $place->getCity()));
        $io->table(
            ['Date', 'Temperature'],
            array_map(fn($m) => [
                $m->getDate()->format('Y-m-d'),
                $m->getTemperature()
            ], $measurements)
        );
        return Command::SUCCESS;
    }
}

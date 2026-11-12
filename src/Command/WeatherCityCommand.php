<?php

namespace App\Command;

use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:city',
    description: 'Pobiera prognozę pogody dla lokalizacji na podstawie kodu kraju i miasta',
)]
class WeatherCityCommand extends Command
{
    public function __construct(
        private WeatherUtil $weatherUtil,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('country', InputArgument::REQUIRED, 'Country Code (e.g. PL)')
            ->addArgument('city', InputArgument::REQUIRED, 'City Name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $country = $input->getArgument('country');
        $city = $input->getArgument('city');

        $measurements = $this->weatherUtil->getWeatherForCountryAndCity($country, $city);

        if (empty($measurements)) {
            $io->error(sprintf('Nie znaleziono pomiarów dla miasta %s (%s).', $city, $country));
            return Command::FAILURE;
        }

        $io->writeln(sprintf('Location: %s (%s)', $city, $country));

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

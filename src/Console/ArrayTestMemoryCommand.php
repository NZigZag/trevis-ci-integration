<?php

namespace TravisCiExample\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TravisCiExample\PHPStan\PHPStanClass;

#[AsCommand(
    name: 'array:test-memory',
    description: 'Array Test Memory',
    aliases: ['array:test-memory'],
    hidden: false
)]
class ArrayTestMemoryCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $array = $this->getLargeArray();
        $this->processArray($array);

        return Command::SUCCESS;
    }

    /**
     * @return  array<int, string> $array
     */
    private function getLargeArray(): array
    {
        echo 'Start M1: ' . $this->getUsageMemory();

        $array = [];
        for ($i = 0; $i < 100_000; $i++) {
            $array[] = uniqid();
        }

        echo 'End M1: ' . $this->getUsageMemory();

        return $array;
    }

    /**
     * @param  array<int, string> $array
     */
    private function processArray(array $array): void
    {
        echo 'Start M2: ' . $this->getUsageMemory();

        //$array[0] = uniqid();
        foreach ($array as $value) {
            uniqid();
        }

        echo 'End M2: ' . $this->getUsageMemory();
    }

    private function getUsageMemory(): string
    {
        return sprintf("%.2FMiB", memory_get_usage(true) / 1024 / 1024) . PHP_EOL;
    }
}

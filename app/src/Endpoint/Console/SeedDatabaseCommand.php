<?php

declare(strict_types=1);

namespace App\Endpoint\Console;

use App\Database\Seeder\DatabaseSeeder;
use Spiral\Console\Command;
use Spiral\Console\Attribute\AsCommand;

#[AsCommand(name: 'db:seed', description: 'Seed the database with test data')]
final class SeedDatabaseCommand extends Command
{
    public function __construct(
        private readonly DatabaseSeeder $seeder
    ) {
        parent::__construct();
    }

    public function __invoke(): int
    {
        $this->info('Starting database seeding...');
        
        try {
            $this->seeder->run();
            $this->info('Database seeded successfully!');
            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error seeding database: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
} 
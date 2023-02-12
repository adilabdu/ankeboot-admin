<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Meilisearch\Client;

class UpdateMeilisearchIndexes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scout:meilisearch-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Meilisearch\'s index and filterable attributes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $client = new Client(env('MEILISEARCH_HOST', 'http://127.0.0.1:7700'));

        $this->updateSortableAttributes($client);

        $this->updateFilterableAttributes($client);

        return Command::SUCCESS;
    }

    protected function updateSortableAttributes(Client $client):void
    {
        $client->index('books')->updateSortableAttributes([
            'id', 'title', 'alternate_title', 'author', 'category', 'type', 'balance', 'code', 'created_at',
        ]);

        $this->info('Updated sortable attributes...');
    }

    protected function updateFilterableAttributes(Client $client): void
    {
        $client->index('books')->updateFilterableAttributes([
            'id', 'title', 'alternate_title', 'author', 'category', 'type', 'balance', 'code', 'created_at',
        ]);

        $this->info('Updated filterable attributes...');
    }
}

<?php

namespace ESG\SyncEngine\Console\Commands;

use ESG\SyncEngine\Models\Category;
use ESG\SyncEngine\Http\Resources\CategoryCollection;
use ESG\SyncEngine\Services\ESGClient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ExportCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export categories';

    public function handle()
    {
        $esgClient = new ESGClient();
        if ($esgClient->isAuthorized()) {
            $this->exportCategories($esgClient);
        } else {
            Log::warning("Unauthorized call");
        }
    }

    public function exportCategories(ESGClient $esgClient)
    {
        // From DB
//            $categories = Category::all();

        // From Json
        $storage = Storage::disk(config('sync.storage.disk'));
        $files = $storage->files(config('sync.storage.categories_path'));
        foreach ($files as $file) {
            $categoriesJson = $storage->get($file);
            $storage->move($file, config('sync.storage.processed_path') . '/' . $file);

            $object = (array)json_decode($categoriesJson);
            $categories = Category::hydrate($object);

            // Mapping
            $categories = new CategoryCollection($categories);

            $res = $esgClient->authPut(config('sync.ep.export.categories'), $categories);
            Log::info($res->getBody());
            $this->info($res->getBody());
        }
    }
}

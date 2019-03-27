<?php

namespace ESG\SyncEngine\Console\Commands;

use ESG\SyncEngine\Http\Resources\PageCollection;
use ESG\SyncEngine\Models\Page;
use ESG\SyncEngine\Services\ESGClient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExportPages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:pages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export pages';

    public function handle()
    {
        $esgClient = new ESGClient();
        if ($esgClient->isAuthorized()) {
            $pages = new PageCollection(Page::all());

            $res = $esgClient->authPut(config('esg.ep.export.pages'), $pages);
            Log::info($res->getBody());
            $this->info($res->getBody());
        } else {
            Log::warning("Unauthorized call");
        }
    }
}

<?php

namespace App\Console\Commands;

use App\Import\ImportRows;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class RunParsing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'excel:parse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command dispatching jobs for parsing excel file by 1000 rows per one job';

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
    public function handle()
    {
        $filePath = Redis::get('toParse');

        if ($filePath !== null) {
            $filePath = Storage::path($filePath);
            Excel::queueImport(new ImportRows, $filePath);
        }

        return 0;
    }
}

<?php

namespace App\Import;

use App\Models\Row;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Redis;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Jobs\AfterImportJob;

class ImportRows implements ToModel, WithChunkReading, ShouldQueue, WithEvents
{
    private $parsing;

    public function __construct($parsing)
    {
        $this->parsing = $parsing;
    }

    public function model(array $row)
    {
        $imported = Redis::get($this->parsing . 'parsing');
        if ($imported !== null)
        {
            $imported += 1;
            Redis::set($this->parsing . 'parsing', $imported);
        } else {
            Redis::set($this->parsing . 'parsing', 1);
        }

        return Row::create([
            'id' => $row[0],
            'name' => $row[1],
            'date' => Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[2]))->format('Y-m-d'),
        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function registerEvents(): array
    {
        return [
            AfterImport::class => function (AfterImport $event) {
                Redis::set($this->parsing . 'parsed', 1);
            }
        ];
    }
}

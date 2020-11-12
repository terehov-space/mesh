<?php

namespace App\Http\Controllers;

use App\Import\ImportRows;
use App\Import\NotifyImported;
use App\Models\Row;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Jobs\AfterImportJob;

class ExcelController extends Controller
{
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'file' => 'required|mimes:xlsx',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $path = $request->file('file')->store('toParse');

        $toParse = str_replace('/', '', $path);

        Redis::set($toParse . 'parsing', 0);
        Redis::set($toParse . 'parsed', 0);

        Excel::queueImport(new ImportRows($toParse), Storage::path($path));

        return redirect()->back()->with('key', $toParse);
    }

    public function check(Request $request)
    {
        $key = $request->input('key');

        $importedCount = Redis::get($key . 'parsing');
        $imported = (bool)Redis::get($key . 'parsed');

        if ($imported) {
            Redis::del($key . 'parsing');
            Redis::del($key . 'parsed');
        }

        return [
            'key' => $key,
            'count' => $importedCount,
            'imported' => $imported,
        ];
    }

    public function getRows(Request $request)
    {
        $rows = Row::get()->groupBy('date');

        return view('rows')->with(compact('rows'));
    }
}

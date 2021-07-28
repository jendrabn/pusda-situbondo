<?php

namespace App\Http\Controllers\Guest;

use App\Exports\IndikatorExport;
use App\Http\Controllers\Controller;
use App\Models\FileIndikator;
use App\Models\FiturIndikator;
use App\Models\IsiIndikator;
use App\Models\Skpd;
use App\Models\TabelIndikator;
use App\Models\UraianIndikator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;

class IndikatorController extends Controller
{
    public function index()
    {
        $categories = TabelIndikator::where('parent_id', 1)->get();
        return view('guest.indikator', compact('categories'));
    }
    public function table($id)
    {
        $tabelIndikator = TabelIndikator::findOrFail($id);
        $uraianIndikator = UraianIndikator::getUraianByTableId($id);
        $fiturIndikator = FiturIndikator::getFiturByTableId($id);
        $years = IsiIndikator::getYears();

        return view('guest.tables.indikator', compact('tabelIndikator', 'uraianIndikator',  'fiturIndikator',  'years'));
    }

    public function export($id)
    {
        $tabelIndikator = TabelIndikator::findOrFail($id);
        $format = request()->input('format');
        if (!in_array($format, ['xlsx', 'csv', 'xls'])) {
            $format = 'xlsx';
        }

        $fileName = "Indikator-{$tabelIndikator->nama_menu}.{$format}";

        return Excel::download(new IndikatorExport($id), $fileName);
    }

    public function getUraianForChart($id)
    {
        $uraianIndikator = UraianIndikator::findOrFail($id);
        $isiIndikator = $uraianIndikator
            ->isiIndikator()
            ->orderByDesc('tahun')
            ->groupBy('tahun')
            ->take(5)
            ->get(['tahun', 'isi']);

        $response = [
            'uraian_id' => $uraianIndikator->id,
            'uraian_parent_id' => $uraianIndikator->parent_id,
            'uraian' => $uraianIndikator->uraian,
            'satuan' => $uraianIndikator->satuan,
            'isi' =>  $isiIndikator,
            'ketersedian_data' => $uraianIndikator->ketersediaan_data
        ];

        return response()->json($response);
    }
}

<?php

namespace App\Http\Traits;

use App\Models\File8KelData;
use App\Models\Fitur8KelData;
use App\Models\Tabel8KelData;
use App\Models\Uraian8KelData;
use App\Models\User;
use App\Services\DelapanKelDataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

trait DelapanKelDataTrait
{
  private DelapanKelDataService $service;

  public function __construct(DelapanKelDataService $service)
  {
    $this->service = $service;
  }

  public function edit(Request $request, Uraian8KelData $uraian)
  {
    $isi = $this->service->getAllIsiByUraianId($uraian);
    $tahuns = $isi->map(fn ($item) => $item->tahun);
    $tabelId = $uraian->tabel_8keldata_id;

    $viewPath = match (request()->user()->role) {
      User::ROLE_ADMIN => 'admin.isi-uraian.edit',
      User::ROLE_SKPD => 'skpd.isi-uraian.edit',
      default => null
    };

    abort_if(!$viewPath, Response::HTTP_NOT_FOUND);

    return view($viewPath, compact('uraian', 'isi', 'tahuns', 'tabelId'));
  }

  public function update(Request $request, Uraian8KelData $uraian)
  {
    $isi = $this->service->getAllIsiByUraianId($uraian);
    $tahuns = $isi->map(fn ($item) => $item->tahun);

    $rules = [
      'uraian' => ['required', 'string'],
      'satuan' => ['required', 'string'],
      'ketersediaan_data' => ['required', 'boolean'],
    ];

    foreach ($tahuns as $tahun) {
      $rules['tahun_' . $tahun] = ['required', 'integer'];
    }

    $this->validate($request, $rules);

    DB::beginTransaction();
    try {
      $uraian->update($request->all());

      $isi->each(function ($item) use ($request) {
        $item->isi = $request->get('tahun_' . $item->tahun);
        $item->save();
      });

      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();

      throw new \Exception($e->getMessage());
    }

    toastr()->addSuccess('');
    return back()->with('success-message', 'Successfully Updated.');
  }

  public function destroy(Uraian8KelData $uraian)
  {
    $uraian->delete();
    toastr()->addSuccess('');
    return back()->with('success-message', 'Successfully Deleted.');
  }

  public function updateFitur(Request $request, Tabel8KelData $tabel)
  {

    $request->validate([
      'deskripsi' => ['nullable', 'string', 'max:255'],
      'analisis'  => ['nullable', 'string', 'max:255'],
      'permasalahan'  => ['nullable', 'string', 'max:255'],
      'solusi'  => ['nullable', 'string', 'max:255'],
      'saran'  => ['nullable', 'string', 'max:255']
    ]);

    $tabel->fitur8KelData()->updateOrCreate([], $request->all());
    toastr()->addSuccess('');
    return back()->with('success-message', 'Successfully Updated');
  }

  public function storeFile(Request $request, Tabel8KelData $tabel)
  {
    $request->validate([
      'document' => ['required', 'max:10240'],
    ]);

    $file = $request->file('document');

    $tabel->file8KelData()->create([
      'nama' => $file->getClientOriginalName(),
      'path' => $file->storePublicly('file_pendukung', 'public')
    ]);
    toastr()->addSuccess('');
    return back()->with('success-message', 'Successfully Saved.');
  }

  public function destroyFile(File8KelData $file)
  {
    Storage::disk('public')->delete($file->path);

    $file->delete();
    toastr()->addSuccess('');
    return back()->with('success-message', 'Successfully Deleted.');
  }

  public function downloadFile(File8KelData $file)
  {
    return Storage::disk('public')->download($file->path, $file->nama);
  }

  public function updateSumberData(Request $request, Uraian8KelData $uraian)
  {
    $request->validate(['skpd_id' => ['required', 'integer', 'exists:skpd,id']]);

    $uraian->skpd_id = $request->skpd_id;
    $uraian->save();

    return response()->json(status: Response::HTTP_NO_CONTENT);
  }

  public function chart(Uraian8KelData $uraian)
  {
    return response()->json($this->service->getChartData($uraian), Response::HTTP_OK);
  }
}

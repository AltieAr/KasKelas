<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KasPeriod;
use Illuminate\Http\Request;

use function Laravel\Prompts\error;

class KasPeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $periods = KasPeriod::orderBy('tanggal_jatuh_tempo', 'desc')->get();
        return response()->json([
            'status'=>'succes',
            'data' => $periods
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'nama_periode' => 'required|string|max:100',
            'nominal_tagihan' => 'required|numeric|min:0',
            'tanggal_jatuh_tempo' => 'required|date|unique:kas_periods,tanggal_jatuh_tempo'
        ]);

        // $tanggal = $validate->tanggal_jatuh_tempo;

        // dd($tanggal);

        // $cek = KasPeriod::where('tanggal_jatuh_tempo', $request->tanggal_jatuh_tempo)->first();
        // if($cek){
        //     return response()->json([
        //         'status'=> false,
        //         'message' => 'periode tanggal ini sudah ada'
        //     ]);
        // }

        $period = KasPeriod::create($validate);
        return response()->json([
            'status' => 'succes',
            'data' => $period
        ],201);
    }


    // Fungsi untuk melihat siapa saja yang sudah/belum bayar di 1 periode tertentu
    public function statusPembayaran($id)
    {
        // 1. Cari data periode tagihannya
        $period = KasPeriod::findOrFail($id);

        // 2. Ambil semua daftar siswa, urutkan berdasarkan absen
        $students = \App\Models\Student::orderBy('no_absen', 'asc')->get();

        // 3. Mapping data untuk menghitung status tiap siswa
        $rekap = $students->map(function ($student) use ($period) {

            // Hitung uang yang disetor siswa INI khusus untuk periode INI
            $totalDibayar = \App\Models\PaymentAllocation::where('student_id', $student->id)
                ->where('period_id', $period->id)
                ->sum('nominal_dibayar');

            $sisaTagihan = $period->nominal_tagihan - $totalDibayar;

            if ($sisaTagihan <= 0) {
                $status = 'Lunas';
            } elseif ($totalDibayar > 0) {
                $status = 'Menyicil';
            } else {
                $status = 'Belum Lunas';
            }

            return [
                'siswa_id' => $student->id,
                'nama' => $student->nama,
                'no_absen' => $student->no_absen,
                'total_terbayar' => $totalDibayar,
                'sisa_tagihan' => $sisaTagihan,
                'status' => $status
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'periode' => [
                    'id' => $period->id,
                    'nama_periode' => $period->nama_periode,
                    'jatuh_tempo' => $period->tanggal_jatuh_tempo,
                    'nominal_tagihan' => $period->nominal_tagihan
                ],
                'laporan_siswa' => $rekap
            ]
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

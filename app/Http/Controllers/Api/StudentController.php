<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KasPeriod;
use App\Models\PaymentAllocation;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::orderBy("no_absen","asc")->get();

        return response()->json([
            'status' => 'succes',
            'data' => $students
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'nama' => 'required|string|max:100',
            'no_absen' => 'required|integer|unique:students,no_absen'
        ]);
        $students = Student::create($validate);
        return response()->json([
            'status' => 'succes',
            'data' => $students
        ],201);
    }

    public function rekapKas($id){
        $student = Student::findOrFail( $id );

        $periods = KasPeriod::orderBy('tanggal_jatuh_tempo', 'asc')->get();


        $rekap = $periods->map(function ($period) use ($student){
            $totalDibayar = PaymentAllocation::where('student_id', $student->id)
            ->where('period_id', $period->id)->sum('nominal_dibayar');

            $sisaTagihan = $period->nominal_tagihan - $totalDibayar;

            // Tentukan label statusnya
            if ($sisaTagihan <= 0) {
                $status = 'Lunas';
            } elseif ($totalDibayar > 0) {
                $status = 'Menyicil'; // Kalau dia baru bayar setengah
            } else {
                $status = 'Belum Lunas';
            }

            return [
                'periode_id' => $period->id,
                'nama_periode' => $period->nama_periode,
                'jatuh_tempo' => $period->tanggal_jatuh_tempo,
                'nominal_tagihan' => $period->nominal_tagihan,
                'total_terbayar' => $totalDibayar,
                'sisa_tagihan' => $sisaTagihan,
                'status' => $status
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'siswa' => [
                    'id' => $student->id,
                    'nama' => $student->nama,
                    'no_absen' => $student->no_absen
                ],
                'ringkasan_tagihan' => $rekap
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

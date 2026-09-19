<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KasPeriod;
use App\Models\PaymentAllocation;
use App\Models\Student;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transaction = Transaction::orderBy('tanggal_transaksi','desc')->get();
        return response()->json([
            'status' => 'succes',
            'data' => $transaction
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $request->validate([
        'student_id' => 'required|exists:students,id',
        'nominal' => 'required|numeric|min:1'
       ]);

       try{
        $result = DB::transaction(function()use($request){
            $student = Student::findOrFail($request->student_id);
            $uangYangDibayar = $request->nominal;
            $keterangan = "pembayaran kas oleh". $student->nama;

            $transaction = Transaction::create([
                'tipe' => 'masuk',
                'nominal' => $uangYangDibayar,
                'tanggal_transaksi' => Carbon::now(),
                'keterangan' => $keterangan
            ]);

            $periods = KasPeriod::orderBy('tanggal_jatuh_tempo', 'asc')->get();

            $alokasiDibuat = [];

            foreach($periods as $period){
                if($uangYangDibayar<=0)break;

                $sudahDibayar = PaymentAllocation::where('student_id', $student->id)
                ->where('period_id', $period->id)->sum('nominal_dibayar');

                $sisaTagihan = $period->nominal_tagihan - $sudahDibayar;

                if($sisaTagihan > 0){
                    $bayarDiPeriodeIni = min($uangYangDibayar, $sisaTagihan);

                    $alokasi = PaymentAllocation::create([
                        'transaction_id' => $transaction->id,
                        'student_id' => $student->id,
                        'period_id' => $period->id,
                        'nominal_dibayar' => $bayarDiPeriodeIni

                    ]);

                    $alokasiDibuat[] = $alokasi;

                    $uangYangDibayar -= $bayarDiPeriodeIni;
                }

            }

            return [
                'transaksi_utama' => $transaction,
                'rincian_alokasi' => $alokasiDibuat,
                'uang_kembalian' => $uangYangDibayar
            ];

        });

        return response()->json([
            'status' => 'succes',
            'data' => $result
        ],201);

       }catch(\Exception $e){
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
       }
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

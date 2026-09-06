<?php

namespace Database\Seeders;

use App\Models\Student;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $students = [
            ['nama' => 'ADRIAN DWI LAKSONO', 'no_absen' => 1],
            ['nama' => 'AMARSYA TERANO', 'no_absen' => 2],
            ['nama' => 'ANAG EKO FIRMANSAH', 'no_absen' => 3],
            ['nama' => 'APRILIYAN BINTEK PURNAMASARI', 'no_absen' => 4],
            ['nama' => 'AULIA SYIFA KUMALA', 'no_absen' => 5],
            ['nama' => 'DELTYN PUTRA UTAMA', 'no_absen' => 6],
            ['nama' => 'DIKI SAHRUL ANAM', 'no_absen' => 7],
            ['nama' => 'ELITONITA MAGDALENA', 'no_absen' => 8],
            ['nama' => 'GENDIS HANIFATI', 'no_absen' =>9],
            ['nama' => 'GHOVIN OIESTHA WAHYU WINOTO', 'no_absen' => 10],
            ['nama' => 'IDAM AHMAD AZUHRI', 'no_absen' => 11],
            ['nama' => 'IKHSAN AL GHUFAHRI', 'no_absen' => 12],
            ['nama' => 'KHANAYA ADIFA WARIDA', 'no_absen' => 13],
            ['nama' => 'LUKMAN FAQIH', 'no_absen' => 14],
            ['nama' => 'MESIAS IMANUEL LAHIA', 'no_absen' => 15],
            ['nama' => 'MOCHAMAD VIKRY MIFTAHUDIN', 'no_absen' => 16],
            ['nama' => 'MUHAMMAD FAKHRI BOIRATAN', 'no_absen' => 17],
            ['nama' => 'MUHAMMAD FEROS ALTAMIS', 'no_absen' => 18],
            ['nama' => 'MUHAMMAD RAFLY HILMAWAN', 'no_absen' => 19],
            ['nama' => 'NABIL MAKARIM ALMUZAFFAR', 'no_absen' => 20],
            ['nama' => 'NOVAL RIVALDO', 'no_absen' => 21],
            ['nama' => 'RADITYA FAKHRI NARARYA', 'no_absen' => 22],
            ['nama' => 'RANGGA ADITIA', 'no_absen' => 23],
            ['nama' => 'RANUM ANGGITA PRIMA GUNTORO', 'no_absen' => 24],
            ['nama' => 'RENDRA OSKI WANDI', 'no_absen' => 25],
            ['nama' => 'ROSALINDA HOTMA', 'no_absen' => 26],
            ['nama' => 'SAMUEL VAN WILSON MARTINO SIAHAAN', 'no_absen' => 27],
            ['nama' => 'SURYA PASETYA', 'no_absen' => 28],
            ['nama' => 'TRIA SAHADI', 'no_absen' => 29],
            ['nama' => 'VINRA AURORA AGLASTA', 'no_absen' => 30],
        ];

        foreach($students as $student) {
            Student::create($student);
        }


    }
}

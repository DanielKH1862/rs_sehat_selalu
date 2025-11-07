<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lokets = [
            [
                'nama_loket' => 'Pendaftaran Umum',
                'deskripsi' => 'Loket untuk pendaftaran pasien baru dan lama',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_loket' => 'Poli Gigi',
                'deskripsi' => 'Loket pelayanan poli gigi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_loket' => 'Poli Umum',
                'deskripsi' => 'Loket pelayanan poli umum',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_loket' => 'Farmasi',
                'deskripsi' => 'Loket pengambilan obat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_loket' => 'Kasir',
                'deskripsi' => 'Loket pembayaran',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('lokets')->insert($lokets);
    }
}

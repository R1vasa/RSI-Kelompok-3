<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kategori')->insert([
            ['id' => 1, 'nama_kategori' => 'Jajan', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nama_kategori' => 'Service', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nama_kategori' => 'Makan', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'nama_kategori' => 'Transportasi', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'nama_kategori' => 'Lain-lain', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

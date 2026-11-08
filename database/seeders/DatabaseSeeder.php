<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Menonaktifkan pengecekan foreign key agar tabel bisa dikosongkan
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();

        // Memasukkan data baru
        DB::table('users')->insert([
            [
                'nama'       => 'Pak Anto',
                'password'   => Hash::make('12345678'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama'       => 'Petugas Shift',
                'password'   => Hash::make('87654321'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
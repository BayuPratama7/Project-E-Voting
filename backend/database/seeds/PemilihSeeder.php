<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Pemilih;

class PemilihSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pemilihData = [
            [
                'nim' => '202001001',
                'nama' => 'Ahmad Rizki',
                'email' => 'ahmad.rizki@student.ac.id',
                'password' => Hash::make('password123'),
                'kelas' => 'SI-7A',
                'semester' => '7',
                'status' => 'active',
                'has_voted' => false,
            ],
            [
                'nim' => '202001002',
                'nama' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@student.ac.id',
                'password' => Hash::make('password123'),
                'kelas' => 'SI-7A',
                'semester' => '7',
                'status' => 'active',
                'has_voted' => false,
            ],
            [
                'nim' => '202001003',
                'nama' => 'Budi Santoso',
                'email' => 'budi.santoso@student.ac.id',
                'password' => Hash::make('password123'),
                'kelas' => 'SI-7B',
                'semester' => '7',
                'status' => 'active',
                'has_voted' => false,
            ],
            [
                'nim' => '202001004',
                'nama' => 'Dewi Kusuma',
                'email' => 'dewi.kusuma@student.ac.id',
                'password' => Hash::make('password123'),
                'kelas' => 'SI-5A',
                'semester' => '5',
                'status' => 'active',
                'has_voted' => false,
            ],
            [
                'nim' => '202001005',
                'nama' => 'Eko Prasetyo',
                'email' => 'eko.prasetyo@student.ac.id',
                'password' => Hash::make('password123'),
                'kelas' => 'SI-5B',
                'semester' => '5',
                'status' => 'active',
                'has_voted' => false,
            ],
            [
                'nim' => '202001006',
                'nama' => 'Fatimah Zahra',
                'email' => 'fatimah.zahra@student.ac.id',
                'password' => Hash::make('password123'),
                'kelas' => 'SI-3A',
                'semester' => '3',
                'status' => 'active',
                'has_voted' => false,
            ],
            [
                'nim' => '202001007',
                'nama' => 'Gunawan Wijaya',
                'email' => 'gunawan.wijaya@student.ac.id',
                'password' => Hash::make('password123'),
                'kelas' => 'SI-3B',
                'semester' => '3',
                'status' => 'active',
                'has_voted' => false,
            ],
            [
                'nim' => '202001008',
                'nama' => 'Hani Suryani',
                'email' => 'hani.suryani@student.ac.id',
                'password' => Hash::make('password123'),
                'kelas' => 'SI-1A',
                'semester' => '1',
                'status' => 'active',
                'has_voted' => false,
            ],
        ];

        foreach ($pemilihData as $data) {
            Pemilih::create($data);
        }

        $this->command->info('Sample pemilih (voters) created successfully!');
    }
}

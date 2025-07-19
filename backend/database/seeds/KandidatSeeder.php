<?php

use Illuminate\Database\Seeder;
use App\Kandidat;

class KandidatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kandidatData = [
            [
                'nama' => 'Muhammad Ridwan',
                'nim' => '201901001',
                'email' => 'muhammad.ridwan@student.ac.id',
                'kelas' => 'SI-7A',
                'semester' => '7',
                'visi' => 'Membangun HIMSI yang solid, inovatif, dan berdaya saing tinggi untuk kemajuan mahasiswa Sistem Informasi.',
                'misi' => 'Meningkatkan kualitas akademik, mengembangkan soft skill mahasiswa, mempererat hubungan dengan industri IT, dan menciptakan program-program yang bermanfaat.',
                'foto' => 'kandidat1.jpg',
                'posisi' => 'ketua',
                'status' => 'active',
                'vote_count' => 0,
            ],
            [
                'nama' => 'Sari Indah Putri',
                'nim' => '201901002',
                'email' => 'sari.indah@student.ac.id',
                'kelas' => 'SI-7A',
                'semester' => '7',
                'visi' => 'Menjadikan HIMSI sebagai wadah pengembangan diri yang inklusif dan mendukung prestasi mahasiswa di berbagai bidang.',
                'misi' => 'Mengadakan pelatihan berkualitas, memfasilitasi kompetisi, meningkatkan networking alumni, dan mengembangkan entrepreneurship mahasiswa.',
                'foto' => 'kandidat2.jpg',
                'posisi' => 'wakil_ketua',
                'status' => 'active',
                'vote_count' => 0,
            ],
            [
                'nama' => 'Arif Rahman Hakim',
                'nim' => '201901003',
                'email' => 'arif.rahman@student.ac.id',
                'kelas' => 'SI-5B',
                'semester' => '5',
                'visi' => 'Menciptakan HIMSI yang transparan, profesional, dan peduli terhadap kesejahteraan anggota.',
                'misi' => 'Meningkatkan transparansi kegiatan, mengoptimalkan fasilitas organisasi, mengadakan program beasiswa, dan mempererat silaturahmi.',
                'foto' => 'kandidat3.jpg',
                'posisi' => 'sekretaris',
                'status' => 'active',
                'vote_count' => 0,
            ],
            [
                'nama' => 'Dian Permata Sari',
                'nim' => '201901004',
                'email' => 'dian.permata@student.ac.id',
                'kelas' => 'SI-5A',
                'semester' => '5',
                'visi' => 'Membangun HIMSI yang mandiri secara finansial dan berkelanjutan dalam setiap program kerjanya.',
                'misi' => 'Mengelola keuangan secara profesional, mengembangkan usaha organisasi, mencari sponsor strategis, dan meningkatkan dana kas.',
                'foto' => 'kandidat4.jpg',
                'posisi' => 'bendahara',
                'status' => 'active',
                'vote_count' => 0,
            ],
            [
                'nama' => 'Ryan Pratama',
                'nim' => '201901005',
                'email' => 'ryan.pratama@student.ac.id',
                'kelas' => 'SI-3A',
                'semester' => '3',
                'visi' => 'Mengembangkan potensi mahasiswa melalui inovasi teknologi dan kreativitas digital.',
                'misi' => 'Mengadakan workshop teknologi terkini, kompetisi programming, project collaboration, dan digital literacy program.',
                'foto' => 'kandidat5.jpg',
                'posisi' => 'anggota',
                'status' => 'active',
                'vote_count' => 0,
            ],
            [
                'nama' => 'Lestari Wulandari',
                'nim' => '201901006',
                'email' => 'lestari.wulandari@student.ac.id',
                'kelas' => 'SI-3B',
                'semester' => '3',
                'visi' => 'Mewujudkan HIMSI yang peduli lingkungan dan sustainable dalam setiap aktivitasnya.',
                'misi' => 'Mengimplementasikan green technology, mengadakan program go digital, dan kampanye peduli lingkungan.',
                'foto' => 'kandidat6.jpg',
                'posisi' => 'anggota',
                'status' => 'active',
                'vote_count' => 0,
            ],
        ];

        foreach ($kandidatData as $data) {
            Kandidat::create($data);
        }

        $this->command->info('Sample kandidat (candidates) created successfully!');
    }
}

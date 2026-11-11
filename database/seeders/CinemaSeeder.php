<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cinema;
use App\Models\Studio;
use App\Models\Seat;
use App\Models\Showtime;
use App\Services\TmdbService;
use Carbon\Carbon;

class CinemaSeeder extends Seeder
{
    public function run(TmdbService $tmdb): void
    {
        // 1. Buat 2 Bioskop (jika belum ada)
        $cinema1 = Cinema::firstOrCreate(
            ['name' => 'CineBook Grand Indonesia'],
            [
                'city' => 'Jakarta Pusat',
                'address' => 'Jl. M.H. Thamrin No.1, Kebon Melati, Tanah Abang',
            ]
        );

        $cinema2 = Cinema::firstOrCreate(
            ['name' => 'CineBook Paris Van Java'],
            [
                'city' => 'Bandung',
                'address' => 'Jl. Sukajadi No.131-139, Cipedes, Sukajadi',
            ]
        );

        // 2. Buat Studio
        $studiosData = [
            ['cinema_id' => $cinema1->id, 'name' => 'Studio 1 (Reguler)', 'type' => 'Regular', 'capacity' => 40],
            ['cinema_id' => $cinema1->id, 'name' => 'Studio IMAX 3D', 'type' => 'IMAX', 'capacity' => 40],
            ['cinema_id' => $cinema2->id, 'name' => 'Studio 1 (Reguler)', 'type' => 'Regular', 'capacity' => 40],
            ['cinema_id' => $cinema2->id, 'name' => 'Studio Premiere', 'type' => 'Premiere', 'capacity' => 30],
        ];

        $studios = [];
        foreach ($studiosData as $data) {
            $studio = Studio::firstOrCreate(
                ['cinema_id' => $data['cinema_id'], 'name' => $data['name']],
                ['type' => $data['type'], 'capacity' => $data['capacity']]
            );
            $studios[] = $studio;

            // Generate Kursi jika studio baru dibuat
            if ($studio->seats()->count() === 0) {
                $rows = ['A', 'B', 'C', 'D', 'E'];
                $cols = ($studio->type === 'Premiere') ? 6 : 8;

                foreach ($rows as $row) {
                    for ($num = 1; $num <= $cols; $num++) {
                        Seat::create([
                            'studio_id' => $studio->id,
                            'seat_code' => $row . $num,
                            'row' => $row,
                            'number' => $num,
                            'type' => ($studio->type === 'Premiere') ? 'vip' : 'regular',
                        ]);
                    }
                }
            }
        }

        // 3. Ambil seluruh film Now Playing dari TMDB dan buat jadwal untuk semuanya
        $nowPlaying = $tmdb->getNowPlaying();
        $movies = $nowPlaying['results'] ?? [];

        foreach ($movies as $index => $movie) {
            // Bergantian pasang di studio berbeda
            $selectedStudio = $studios[$index % count($studios)];
            $price = ($selectedStudio->type === 'IMAX') ? 75000 : (($selectedStudio->type === 'Premiere') ? 100000 : 45000);

            // Buat 3 jadwal untuk hari ini dan besok
            $hours = [13, 16, 19, 21];
            foreach ($hours as $h) {
                // Buat jadwal untuk BESOK agar jamnya tidak pernah kadaluwarsa
                $startTime = Carbon::tomorrow()->setHour($h)->setMinute(0)->setSecond(0);
                $endTime = (clone $startTime)->addMinutes(120);

                Showtime::firstOrCreate(
                    [
                        'studio_id' => $selectedStudio->id,
                        'tmdb_movie_id' => $movie['id'],
                        'start_time' => $startTime,
                    ],
                    [
                        'movie_title' => $movie['title'],
                        'movie_poster' => $movie['poster_path'] ?? null,
                        'end_time' => $endTime,
                        'price' => $price,
                    ]
                );
            }
        }
    }
}
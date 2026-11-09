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
        // 1. Buat 2 Bioskop
        $cinema1 = Cinema::create([
            'name' => 'CineBook Grand Indonesia',
            'city' => 'Jakarta Pusat',
            'address' => 'Jl. M.H. Thamrin No.1, Kebon Melati, Tanah Abang',
        ]);

        $cinema2 = Cinema::create([
            'name' => 'CineBook Paris Van Java',
            'city' => 'Bandung',
            'address' => 'Jl. Sukajadi No.131-139, Cipedes, Sukajadi',
        ]);

        // 2. Buat Studio untuk masing-masing bioskop
        $studios = [
            ['cinema_id' => $cinema1->id, 'name' => 'Studio 1 (Reguler)', 'type' => 'Regular', 'capacity' => 40],
            ['cinema_id' => $cinema1->id, 'name' => 'Studio IMAX 3D', 'type' => 'IMAX', 'capacity' => 40],
            ['cinema_id' => $cinema2->id, 'name' => 'Studio 1 (Reguler)', 'type' => 'Regular', 'capacity' => 40],
            ['cinema_id' => $cinema2->id, 'name' => 'Studio Premiere', 'type' => 'Premiere', 'capacity' => 30],
        ];

        foreach ($studios as $studioData) {
            $studio = Studio::create($studioData);

            // 3. Generate Kursi Otomatis (Baris A-E, Nomor 1-8 = 40 kursi)
            $rows = ['A', 'B', 'C', 'D', 'E'];
            $cols = ($studio->type === 'Premiere') ? 6 : 8; // Premiere 30 kursi, Regular/IMAX 40 kursi

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

        // 4. Ambil film TMDB yang sedang tayang untuk diberi jadwal
        try {
            $movies = $tmdb->getNowPlaying()['results'] ?? [];
            $allStudios = Studio::all();

            // Beri jadwal untuk 4 film pertama TMDB
            foreach (array_slice($movies, 0, 4) as $index => $movie) {
                $selectedStudio = $allStudios[$index % count($allStudios)];
                $price = ($selectedStudio->type === 'IMAX') ? 75000 : (($selectedStudio->type === 'Premiere') ? 100000 : 45000);

                // Buat 3 jadwal tayang untuk hari ini dan besok
                $hours = [13, 16, 19];
                foreach ($hours as $h) {
                    $startTime = Carbon::today()->setHour($h)->setMinute(0)->setSecond(0);
                    // Jika jam sudah lewat hari ini, geser ke besok
                    if ($startTime->isPast()) {
                        $startTime->addDay();
                    }
                    $endTime = (clone $startTime)->addMinutes(120);

                    // Validasi anti bentrok sebelum disimpan
                    if (!Showtime::hasOverlap($selectedStudio->id, $startTime->toDateTimeString(), $endTime->toDateTimeString())) {
                        Showtime::create([
                            'studio_id' => $selectedStudio->id,
                            'tmdb_movie_id' => $movie['id'],
                            'movie_title' => $movie['title'],
                            'movie_poster' => $movie['poster_path'] ?? null,
                            'start_time' => $startTime,
                            'end_time' => $endTime,
                            'price' => $price,
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            // Biarkan lewat jika TMDB ada kendala koneksi
        }
    }
}
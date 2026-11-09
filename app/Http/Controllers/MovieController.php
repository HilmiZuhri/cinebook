<?php

namespace App\Http\Controllers;

use App\Services\TmdbService;
use Illuminate\Http\Request;
use App\Models\Showtime;

class MovieController extends Controller
{
    protected TmdbService $tmdb;

    public function __construct(TmdbService $tmdb)
    {
        $this->tmdb = $tmdb;
    }

    /**
     * Halaman Beranda: Hero Banner + Now Playing + Upcoming
     */
    public function index()
    {
        $nowPlaying = $this->tmdb->getNowPlaying();
        $upcoming = $this->tmdb->getUpcoming();

        $movies = $nowPlaying['results'] ?? [];
        $heroMovie = $movies[0] ?? null; // Film pertama dijadikan Hero Banner

        return view('movies.index', [
            'heroMovie' => $heroMovie,
            'nowPlaying' => array_slice($movies, 1, 10), // 10 film sedang tayang
            'upcoming' => array_slice($upcoming['results'] ?? [], 0, 10), // 10 film akan datang
            'tmdb' => $this->tmdb,
        ]);
    }

    /**
     * Halaman Katalog / Pencarian Film
     */
    public function catalog(Request $request)
    {
        $search = $request->query('q');

        if ($search) {
            $response = $this->tmdb->searchMovies($search);
            $title = "Hasil Pencarian: \"{$search}\"";
        } else {
            $response = $this->tmdb->getPopular();
            $title = "Katalog Film Populer";
        }

        return view('movies.catalog', [
            'movies' => $response['results'] ?? [],
            'title' => $title,
            'search' => $search,
            'tmdb' => $this->tmdb,
        ]);
    }

    /**
     * Halaman Detail Film
     */
    public function show($id)
    {
        $movie = $this->tmdb->getMovieDetails($id);

        // Cari trailer YouTube
        $trailerKey = null;
        if (!empty($movie['videos']['results'])) {
            foreach ($movie['videos']['results'] as $video) {
                if ($video['site'] === 'YouTube' && ($video['type'] === 'Trailer' || $video['type'] === 'Teaser')) {
                    $trailerKey = $video['key'];
                    break;
                }
            }
        }

        // Ambil jadwal tayang film ini, kelompokkan berdasarkan nama Bioskop
        $showtimes = Showtime::with(['studio.cinema'])
            ->where('tmdb_movie_id', $id)
            ->where('start_time', '>=', now())
            ->orderBy('start_time')
            ->get()
            ->groupBy(function ($item) {
                return $item->studio->cinema->name;
            });

        return view('movies.show', [
            'movie' => $movie,
            'trailerKey' => $trailerKey,
            'showtimes' => $showtimes,
            'tmdb' => $this->tmdb,
        ]);
    }
}
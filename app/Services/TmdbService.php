<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Exception;

class TmdbService
{
    protected string $baseUrl;
    protected string $imageBaseUrl;
    protected ?string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.tmdb.base_url');
        $this->imageBaseUrl = config('services.tmdb.image_base_url');
        $this->apiKey = config('services.tmdb.api_key');
    }

    /**
     * Helper untuk memanggil TMDB API
     */
    protected function get(string $endpoint, array $params = []): array
    {
        if (empty($this->apiKey)) {
            throw new Exception('TMDB API Key belum dikonfigurasi di file .env');
        }

        $params['api_key'] = $this->apiKey;
        $params['language'] = $params['language'] ?? 'id-ID'; // Mengambil data bahasa Indonesia jika tersedia

        $response = Http::baseUrl($this->baseUrl)->get($endpoint, $params);

        if ($response->failed()) {
            throw new Exception("Gagal mengambil data dari TMDB API: " . $response->body());
        }

        return $response->json();
    }

    /**
     * Film yang sedang tayang (Now Playing) dengan Cache 2 jam
     */
    public function getNowPlaying(int $page = 1): array
    {
        return Cache::remember("tmdb_now_playing_page_{$page}", now()->addHours(2), function () use ($page) {
            return $this->get('/movie/now_playing', ['page' => $page]);
        });
    }

    /**
     * Film populer dengan Cache 2 jam
     */
    public function getPopular(int $page = 1): array
    {
        return Cache::remember("tmdb_popular_page_{$page}", now()->addHours(2), function () use ($page) {
            return $this->get('/movie/popular', ['page' => $page]);
        });
    }

    /**
     * Film yang akan datang (Upcoming) dengan Cache 2 jam
     */
    public function getUpcoming(int $page = 1): array
    {
        return Cache::remember("tmdb_upcoming_page_{$page}", now()->addHours(2), function () use ($page) {
            return $this->get('/movie/upcoming', ['page' => $page]);
        });
    }

    /**
     * Pencarian film (Cache 30 menit per keyword)
     */
    public function searchMovies(string $query, int $page = 1): array
    {
        $cacheKey = "tmdb_search_" . md5($query) . "_{$page}";
        return Cache::remember($cacheKey, now()->addMinutes(30), function () use ($query, $page) {
            return $this->get('/search/movie', [
                'query' => $query,
                'page' => $page,
            ]);
        });
    }

    /**
     * Detail film lengkap (termasuk trailer video dan cast pemain) dengan Cache 6 jam
     */
    public function getMovieDetails(int|string $movieId): array
    {
        return Cache::remember("tmdb_movie_detail_{$movieId}", now()->addHours(6), function () use ($movieId) {
            return $this->get("/movie/{$movieId}", [
                'append_to_response' => 'videos,credits',
            ]);
        });
    }

    /**
     * Helper untuk menghasilkan URL gambar lengkap
     * Ukuran poster: w500, backdrop: original atau w1280
     */
    public function getImageUrl(?string $path, string $size = 'w500'): ?string
    {
        if (! $path) {
            return null;
        }

        return rtrim($this->imageBaseUrl, '/') . "/{$size}" . $path;
    }
}
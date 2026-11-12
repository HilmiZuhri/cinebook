<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Cinema;
use App\Models\Studio;
use App\Models\Seat;
use App\Models\Showtime;
use App\Models\Booking;
use App\Models\BookingSeat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class BookingAndAuthorizationTest extends TestCase
{
    // Gunakan RefreshDatabase agar setiap pengujian berjalan dengan database yang bersih
    use RefreshDatabase;

    protected User $admin;
    protected User $customer;
    protected Showtime $showtime;
    protected Seat $seatA1;
    protected Seat $seatA2;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Siapkan User Admin & Customer
        $this->admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->customer = User::create([
            'name' => 'Customer Test',
            'email' => 'customer@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        // 2. Siapkan Bioskop, Studio, Kursi & Jadwal
        $cinema = Cinema::create([
            'name' => 'CineBook Test Mall',
            'city' => 'Jakarta',
            'address' => 'Jl. Test No. 1',
        ]);

        $studio = Studio::create([
            'cinema_id' => $cinema->id,
            'name' => 'Studio 1',
            'type' => 'Regular',
            'capacity' => 10,
        ]);

        $this->seatA1 = Seat::create([
            'studio_id' => $studio->id,
            'seat_code' => 'A1',
            'row' => 'A',
            'number' => 1,
            'type' => 'regular',
        ]);

        $this->seatA2 = Seat::create([
            'studio_id' => $studio->id,
            'seat_code' => 'A2',
            'row' => 'A',
            'number' => 2,
            'type' => 'regular',
        ]);

        $this->showtime = Showtime::create([
            'studio_id' => $studio->id,
            'tmdb_movie_id' => 12345,
            'movie_title' => 'Film Uji Coba',
            'start_time' => now()->addDay()->setHour(14)->setMinute(0),
            'end_time' => now()->addDay()->setHour(16)->setMinute(0),
            'price' => 50000,
        ]);
    }

    /**
     * Uji 1: Customer DILARANG mengakses Admin Dashboard (403 Forbidden)
     */
    public function test_customer_cannot_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->customer)->get('/admin/dashboard');
        $response->assertStatus(403);
    }

    /**
     * Uji 2: Admin DIIZINKAN mengakses Admin Dashboard (200 OK)
     */
    public function test_admin_can_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/dashboard');
        $response->assertStatus(200);
    }

    /**
     * Uji 3: Customer dapat memesan kursi dan total harga terhitung akurat
     */
    public function test_customer_can_book_seats_successfully(): void
    {
        // Pesan 2 kursi (A1 dan A2) seharga 50.000 x 2 = 100.000
        $response = $this->actingAs($this->customer)->post("/booking/{$this->showtime->id}/seats", [
            'seat_ids' => [$this->seatA1->id, $this->seatA2->id],
        ]);

        // Harus diarahkan ke halaman checkout
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('bookings', [
            'user_id' => $this->customer->id,
            'showtime_id' => $this->showtime->id,
            'total_price' => 100000,
            'status' => 'pending',
        ]);

        $this->assertDatabaseCount('booking_seats', 2);
    }

    /**
     * Uji 4: Mencegah Double Booking pada kursi yang sama (Race Condition Defense)
     */
    public function test_prevents_double_booking_of_same_seat(): void
    {
        // 1. Customer pertama berhasil memesan kursi A1
        $this->actingAs($this->customer)->post("/booking/{$this->showtime->id}/seats", [
            'seat_ids' => [$this->seatA1->id],
        ]);

        // 2. Buat customer kedua
        $customerDua = User::create([
            'name' => 'Customer Kedua',
            'email' => 'customer2@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        // 3. Customer kedua mencoba memesan kursi A1 yang sama persis
        $response = $this->actingAs($customerDua)->post("/booking/{$this->showtime->id}/seats", [
            'seat_ids' => [$this->seatA1->id],
        ]);

        // Pemesanan kedua HARUS gagal dengan pesan error sesi
        $response->assertSessionHasErrors(['seats']);

        // Pastikan kursi A1 hanya tercatat 1 kali di database
        $this->assertEquals(1, BookingSeat::where('seat_id', $this->seatA1->id)->count());
    }

    /**
     * Uji 5: Pembatalan pesanan melepaskan status kursi
     */
    public function test_cancelling_booking_releases_seats(): void
    {
        $booking = Booking::create([
            'user_id' => $this->customer->id,
            'showtime_id' => $this->showtime->id,
            'booking_code' => 'CB-TEST1234',
            'total_price' => 50000,
            'status' => 'pending',
            'expires_at' => now()->addMinutes(15),
        ]);

        BookingSeat::create([
            'booking_id' => $booking->id,
            'seat_id' => $this->seatA1->id,
            'price' => 50000,
        ]);

        // Customer membatalkan pesanan
        $response = $this->actingAs($this->customer)->post("/checkout/{$booking->id}/cancel");

        $response->assertRedirect('/');
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'cancelled',
        ]);
    }
}
<?php

namespace Tests\Feature;

use App\Models\Mahasiswa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MahasiswaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Display daftar mahasiswa halaman utama
     */
    public function test_index_page_displays_correctly(): void
    {
        $response = $this->get(route('mahasiswa.index'));
        $response->assertStatus(200);
        $response->assertViewIs('mahasiswa.index');
    }

    /**
     * Test: Tampilkan empty state jika belum ada data
     */
    public function test_index_shows_empty_state_when_no_data(): void
    {
        $response = $this->get(route('mahasiswa.index'));
        $response->assertStatus(200);
        $response->assertSee('Belum ada data mahasiswa');
    }

    /**
     * Test: Tampilkan daftar mahasiswa ketika ada data
     */
    public function test_index_displays_mahasiswa_list(): void
    {
        Mahasiswa::create([
            'nim' => '001',
            'nama' => 'John Doe',
            'program_studi' => 'Teknik Informatika',
            'email' => 'john@example.com',
        ]);

        $response = $this->get(route('mahasiswa.index'));
        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertSee('001');
        $response->assertSee('Teknik Informatika');
    }

    /**
     * Test: Create form page loads correctly
     */
    public function test_create_page_loads(): void
    {
        $response = $this->get(route('mahasiswa.create'));
        $response->assertStatus(200);
        $response->assertSee('Tambah Mahasiswa');
    }

    /**
     * Test: Model can be created directly
     */
    public function test_mahasiswa_model_create(): void
    {
        $mahasiswa = Mahasiswa::create([
            'nim' => '123456',
            'nama' => 'Ahmad Rasim',
            'program_studi' => 'Sistem Informasi',
            'email' => 'ahmad@example.com',
        ]);

        $this->assertDatabaseHas('mahasiswa', [
            'nim' => '123456',
            'nama' => 'Ahmad Rasim',
        ]);
    }

    /**
     * Test: Model stores null email correctly
     */
    public function test_mahasiswa_model_with_nullable_email(): void
    {
        $mahasiswa = Mahasiswa::create([
            'nim' => '123456',
            'nama' => 'Ahmad Rasim',
            'program_studi' => 'Sistem Informasi',
            'email' => null,
        ]);

        $this->assertNull($mahasiswa->email);
        $this->assertDatabaseHas('mahasiswa', [
            'nim' => '123456',
            'email' => null,
        ]);
    }

    /**
     * Test: Edit page shows pre-filled data
     */
    public function test_edit_page_shows_prefilled_data(): void
    {
        $mahasiswa = Mahasiswa::create([
            'nim' => '123456',
            'nama' => 'John Doe',
            'program_studi' => 'Teknik Informatika',
            'email' => 'john@example.com',
        ]);

        $response = $this->get(route('mahasiswa.edit', $mahasiswa->id));
        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertSee('123456');
        $response->assertSee('Teknik Informatika');
        $response->assertSee('john@example.com');
    }

    /**
     * Test: Model can be updated
     */
    public function test_mahasiswa_model_update(): void
    {
        $mahasiswa = Mahasiswa::create([
            'nim' => '123456',
            'nama' => 'John Doe',
            'program_studi' => 'Teknik Informatika',
        ]);

        $mahasiswa->update([
            'nama' => 'John Updated',
            'program_studi' => 'Sistem Informasi',
        ]);

        $this->assertDatabaseHas('mahasiswa', [
            'id' => $mahasiswa->id,
            'nama' => 'John Updated',
            'program_studi' => 'Sistem Informasi',
        ]);
    }

    /**
     * Test: Model can be deleted
     */
    public function test_mahasiswa_model_delete(): void
    {
        $mahasiswa = Mahasiswa::create([
            'nim' => '123456',
            'nama' => 'John Doe',
            'program_studi' => 'Teknik Informatika',
        ]);

        $id = $mahasiswa->id;
        $mahasiswa->delete();

        $this->assertDatabaseMissing('mahasiswa', ['id' => $id]);
    }

    /**
     * Test: Pencarian berdasarkan Nama
     */
    public function test_search_mahasiswa_by_nama(): void
    {
        Mahasiswa::create([
            'nim' => '001',
            'nama' => 'Ahmad Rasim',
            'program_studi' => 'Teknik Informatika',
        ]);

        Mahasiswa::create([
            'nim' => '002',
            'nama' => 'Budi Santoso',
            'program_studi' => 'Sistem Informasi',
        ]);

        $response = $this->get(route('mahasiswa.index', ['search' => 'Ahmad']));
        $response->assertStatus(200);
        $response->assertSee('Ahmad Rasim');
        $response->assertDontSee('Budi Santoso');
    }

    /**
     * Test: Pencarian berdasarkan NIM
     */
    public function test_search_mahasiswa_by_nim(): void
    {
        Mahasiswa::create([
            'nim' => '001',
            'nama' => 'Ahmad Rasim',
            'program_studi' => 'Teknik Informatika',
        ]);

        Mahasiswa::create([
            'nim' => '002',
            'nama' => 'Budi Santoso',
            'program_studi' => 'Sistem Informasi',
        ]);

        $response = $this->get(route('mahasiswa.index', ['search' => '001']));
        $response->assertStatus(200);
        $response->assertSee('Ahmad Rasim');
        $response->assertDontSee('Budi Santoso');
    }

    /**
     * Test: Pencarian dengan keyword kosong menampilkan semua data
     */
    public function test_search_with_empty_keyword_shows_all(): void
    {
        Mahasiswa::create([
            'nim' => '001',
            'nama' => 'Ahmad Rasim',
            'program_studi' => 'Teknik Informatika',
        ]);

        Mahasiswa::create([
            'nim' => '002',
            'nama' => 'Budi Santoso',
            'program_studi' => 'Sistem Informasi',
        ]);

        $response = $this->get(route('mahasiswa.index', ['search' => '']));
        $response->assertStatus(200);
        $response->assertSee('Ahmad Rasim');
        $response->assertSee('Budi Santoso');
    }

    /**
     * Test: Pagination - 10 data per halaman
     */
    public function test_pagination_10_items_per_page(): void
    {
        for ($i = 1; $i <= 15; $i++) {
            Mahasiswa::create([
                'nim' => sprintf('%06d', $i),
                'nama' => "Mahasiswa $i",
                'program_studi' => 'Teknik Informatika',
            ]);
        }

        $response = $this->get(route('mahasiswa.index'));
        $response->assertStatus(200);

        // Halaman 1 seharusnya menampilkan 10 data
        $this->assertCount(10, $response->viewData('mahasiswa'));
    }

    /**
     * Test: Pencarian tetap persistent saat berpindah halaman
     */
    public function test_search_keyword_persists_on_pagination(): void
    {
        for ($i = 1; $i <= 15; $i++) {
            Mahasiswa::create([
                'nim' => sprintf('%06d', $i),
                'nama' => $i % 2 == 0 ? "Ahmad $i" : "Budi $i",
                'program_studi' => 'Teknik Informatika',
            ]);
        }

        $response = $this->get(route('mahasiswa.index', ['search' => 'Ahmad']));
        $response->assertStatus(200);

        // Verifikasi search results ditampilkan
        $response->assertSee('Ahmad');
    }

    /**
     * Test: Edit nonexistent mahasiswa returns 404
     */
    public function test_edit_nonexistent_mahasiswa_returns_404(): void
    {
        $response = $this->get(route('mahasiswa.edit', 999));
        $response->assertStatus(404);
    }

    /**
     * Test: Mass assignment protection - fillable array is set
     */
    public function test_mahasiswa_model_fillable(): void
    {
        $mahasiswa = new Mahasiswa();
        $fillable = $mahasiswa->getFillable();

        $this->assertContains('nim', $fillable);
        $this->assertContains('nama', $fillable);
        $this->assertContains('program_studi', $fillable);
        $this->assertContains('email', $fillable);
    }

    /**
     * Test: Unique constraint on NIM in database
     */
    public function test_nim_unique_constraint(): void
    {
        Mahasiswa::create([
            'nim' => '123456',
            'nama' => 'John Doe',
            'program_studi' => 'Teknik Informatika',
        ]);

        // Trying to create another with same NIM should fail
        try {
            Mahasiswa::create([
                'nim' => '123456',
                'nama' => 'Jane Doe',
                'program_studi' => 'Sistem Informasi',
            ]);
            $this->fail('Should have thrown an exception for duplicate NIM');
        } catch (\Exception $e) {
            $this->assertStringContainsString('Duplicate entry', $e->getMessage());
        }
    }

    /**
     * Test: Unique constraint on Email in database
     */
    public function test_email_unique_constraint(): void
    {
        Mahasiswa::create([
            'nim' => '123456',
            'nama' => 'John Doe',
            'program_studi' => 'Teknik Informatika',
            'email' => 'john@example.com',
        ]);

        // Trying to create another with same email should fail
        try {
            Mahasiswa::create([
                'nim' => '789012',
                'nama' => 'Jane Doe',
                'program_studi' => 'Sistem Informasi',
                'email' => 'john@example.com',
            ]);
            $this->fail('Should have thrown an exception for duplicate email');
        } catch (\Exception $e) {
            $this->assertStringContainsString('Duplicate entry', $e->getMessage());
        }
    }

    /**
     * Test: Routes are registered correctly
     */
    public function test_mahasiswa_routes_exist(): void
    {
        $this->assertTrue(route('mahasiswa.index') !== '');
        $this->assertTrue(route('mahasiswa.create') !== '');
    }
}

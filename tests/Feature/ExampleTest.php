<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */

    public function test_login_page(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_dashboard(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect(route('login'));
        $response->assertStatus(302);
    }

    public function test_melihat_halaman_dashboard_setelah_login(): void
    {
        Role::findOrCreate('admin');
        Permission::findOrCreate('view');

        $user = User::firstOrCreate([
            'email' => 'ardi@gmail.com',
        ], [
            'name' => 'Ardi',
            'roles_id' => '1',
            'password' => bcrypt('12345678'),
        ]);

        $user->assignRole('admin');

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
    }

    public function test_menampilkan_halaman_kategori(): void
    {
        Role::findOrCreate('admin');
        Permission::findOrCreate('view');

        $user = User::firstOrCreate([
            'email' => 'ardi@gmail.com',
        ], [
            'name' => 'Ardi',
            'roles_id' => '1',
            'password' => bcrypt('12345678'),
        ]);

        $user->assignRole('admin');

        $response = $this->actingAs($user)->get('/kategori');

        $response->assertStatus(200);
        $response->assertSee('Daftar Kategori');
    }

    public function test_menampilkan_form_tambah_data_kategori(): void
    {
        Role::findOrCreate('admin');
        Permission::findOrCreate('crud');

        $user = User::firstOrCreate([
            'email' => 'ardi@gmail.com',
        ], [
            'name' => 'Ardi',
            'roles_id' => '1',
            'password' => bcrypt('12345678'),
        ]);

        $user->assignRole('admin');

        $response = $this->actingAs($user)->get('/kategori/create');
        $response->assertStatus(200);
        $response->assertSee('Tambah Kategori');
    }

    public function test_menambah_data_kategori(): void
    {
        Role::findOrCreate('admin');
        Permission::findOrCreate('crud');

        $user = User::firstOrCreate([
            'email' => 'ardi@gmail.com',
        ], [
            'name' => 'Ardi',
            'roles_id' => '1',
            'password' => bcrypt('12345678'),
        ]);

        $user->assignRole('admin');

        $response = $this->actingAs($user)->get('/kategori/create');
        
        $data = [
            'nama_kategori' => 'Minuman'
        ];
        
        $response = $this->actingAs($user)->post('/kategori', $data);
        
        $response->assertRedirect('/kategori');
        
        $response->assertStatus(302);
        
        $this->assertDatabaseHas('kategori', $data);
        
        $response->assertSessionHas('success', 'Kategori berhasil ditambahkan');
    }


}
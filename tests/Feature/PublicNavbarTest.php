<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicNavbarTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'mysql',
            'database.connections.mysql.database' => 'sekolah_modern',
        ]);
    }
    public function test_homepage_renders_navbar_with_mobile_toggle_and_menu(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('school-navbar');
        $response->assertSee('navToggleBtn');
        $response->assertSee('navMobileMenu');
        $response->assertSee('Beranda');
        $response->assertSee('Profil Sekolah');
        $response->assertSee('Pengajar');
        $response->assertSee('Akademik');
        $response->assertSee('Kesiswaan');
        $response->assertSee('PPDB Online');
        $response->assertSee('Media');
    }

    public function test_public_pages_render_navbar_consistently(): void
    {
        $routes = [
            'profile.show',
            'teachers.show',
            'academic.show',
            'student-activity.show',
            'ppdb.index',
            'contact.show',
        ];

        foreach ($routes as $routeName) {
            $response = $this->get(route($routeName));
            $response->assertStatus(200);
            $response->assertSee('navToggleBtn');
            $response->assertSee('navMobileMenu');
        }
    }
}

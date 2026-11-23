<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_redirects_to_dashboard(): void
    {
        $this->get('/')->assertRedirect('/admin/dashboard');
    }

    public function test_dashboard_page_loads(): void
    {
        $this->get('/admin/dashboard')->assertOk();
    }

    public function test_resource_index_pages_load(): void
    {
        $this->get('/admin/ponds')->assertOk();
        $this->get('/admin/fishes')->assertOk();
        $this->get('/admin/measurements')->assertOk();
        $this->get('/admin/history')->assertOk();
        $this->get('/admin/analysis')->assertOk();
    }
}

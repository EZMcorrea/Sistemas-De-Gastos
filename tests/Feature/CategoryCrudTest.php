<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_update_and_delete_category()
    {
        // Create
        $response = $this->post('/categorias', ['name' => 'CategoriaTeste']);
        $response->assertRedirect();
        $this->assertDatabaseHas('categories', ['name' => 'CategoriaTeste']);

        $cat = Category::where('name', 'CategoriaTeste')->first();
        $this->assertNotNull($cat);

        // Update
        $res2 = $this->put('/categorias/' . $cat->id, ['name' => 'CategoriaAtualizada']);
        $res2->assertRedirect();
        $this->assertDatabaseHas('categories', ['name' => 'CategoriaAtualizada']);

        // Delete
        $res3 = $this->delete('/categorias/' . $cat->id);
        $res3->assertRedirect();
        $this->assertDatabaseMissing('categories', ['id' => $cat->id]);
    }
}

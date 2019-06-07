<?php

namespace Tests\Unit;

use App\Book;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookTest extends TestCase
{
    public function test_can_create_book() {
        $data = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph
        ];
        $this->post('/api/v1/books', $data)->assertStatus(200)->assertJson($data);
    }

    public function test_can_update_book() {
        $book = factory(Book::class)->create();
        $data = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph
        ];
        $this->patch('/api/v1/books/' . $book->id, $data)->assertStatus(200)->assertJson($data);
    }

    public function test_can_show_book() {
        $book = factory(Book::class)->create();
        $this->get('/api/v1/books/' . $book->id)->assertStatus(200);
    }

    public function test_can_delete_book() {
        $book = factory(Book::class)->create();
        $this->delete('/api/v1/books/' . $book->id)->assertStatus(200);
    }
}

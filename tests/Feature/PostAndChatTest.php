<?php

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('authenticated user can create a post', function () {
    Storage::fake('public');

    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post('/posts', [
            'body' => 'Bu birinchi test posti',
            'images' => [
                UploadedFile::fake()->image('test1.jpg'),
            ],
        ]);

    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('posts', [
        'user_id' => $user->id,
        'body' => 'Bu birinchi test posti',
    ]);
});

test('user can comment on a post and delete their comment', function () {
    $author = User::factory()->create();
    $commenter = User::factory()->create();
    $post = Post::create([
        'user_id' => $author->id,
        'body' => 'Post matni',
    ]);

    $response = $this
        ->actingAs($commenter)
        ->post("/posts/{$post->id}/comments", [
            'body' => 'Yaxshi post ekan!',
        ]);

    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('comments', [
        'user_id' => $commenter->id,
        'post_id' => $post->id,
        'body' => 'Yaxshi post ekan!',
    ]);

    $comment = Comment::where('post_id', $post->id)->first();
    expect($comment->post)->not->toBeNull();
    expect($comment->post->id)->toBe($post->id);

    // Post egasi boshqa birovning izohini o'chira oladi (post relation tekshiriladi)
    $deleteResponse = $this
        ->actingAs($author)
        ->delete("/comments/{$comment->id}");

    $deleteResponse->assertSessionHasNoErrors();
    $this->assertDatabaseMissing('comments', [
        'id' => $comment->id,
    ]);
});

test('user can like and unlike a post', function () {
    $user = User::factory()->create();
    $post = Post::create([
        'user_id' => $user->id,
        'body' => 'Like testi uchun post',
    ]);

    // Like bosish
    $this->actingAs($user)->post("/posts/{$post->id}/like");
    $this->assertDatabaseHas('likes', [
        'user_id' => $user->id,
        'post_id' => $post->id,
    ]);

    // Like qaytarib olish
    $this->actingAs($user)->post("/posts/{$post->id}/like");
    $this->assertDatabaseMissing('likes', [
        'user_id' => $user->id,
        'post_id' => $post->id,
    ]);
});

test('chat message validates file mime types', function () {
    Storage::fake('public');

    $sender = User::factory()->create();
    $receiver = User::factory()->create();

    // Zararli yoki ruxsat etilmagan fayl yuklanishi rad etilishi kerak
    $response = $this
        ->actingAs($sender)
        ->postJson("/chat/{$receiver->id}/send", [
            'audio' => UploadedFile::fake()->create('malicious.php', 100),
        ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['audio']);
});

test('authenticated user can create a text-only post without images', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post('/posts', [
            'body' => 'Faqatgina matndan iborat post',
        ]);

    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('posts', [
        'user_id' => $user->id,
        'body' => 'Faqatgina matndan iborat post',
        'image' => null,
    ]);
});

test('authenticated user can create and update a post with custom gradient theme', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post('/posts', [
            'body' => 'Chiroyli gradient mavzudagi post',
            'theme' => 'emerald',
        ]);

    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('posts', [
        'user_id' => $user->id,
        'body' => 'Chiroyli gradient mavzudagi post',
        'theme' => 'emerald',
    ]);

    $post = Post::where('user_id', $user->id)->first();
    expect($post->gradient_class)->toContain('from-[#022c22]');

    // Postni tahrirlash va mavzuni o'zgartirish
    $updateResponse = $this
        ->actingAs($user)
        ->patch("/posts/{$post->id}", [
            'body' => 'Tahrirlangan matn',
            'theme' => 'sunset',
        ]);

    $updateResponse->assertSessionHasNoErrors();
    $post->refresh();
    expect($post->theme)->toBe('sunset');
    expect($post->gradient_class)->toContain('from-[#881337]');
});


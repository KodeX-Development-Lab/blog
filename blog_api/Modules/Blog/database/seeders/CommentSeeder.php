<?php
namespace Modules\Blog\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Blog\Models\Comment;
use Modules\Blog\Models\Post;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userArr = User::take(20)->pluck('id')->toArray();
        $len     = count($userArr);
        $max     = $len - 1;

        $posts = Post::get();

        foreach ($posts as $key => $post) {
            Comment::factory()
                ->count(rand(1, 20))
                ->create([
                    'user_id' => $userArr[rand(1, $max)],
                    'post_id' => $post->id,
                ]);
        }

    }
}

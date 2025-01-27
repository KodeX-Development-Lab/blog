<?php
namespace Modules\Blog\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Blog\Models\Post;
use Modules\Blog\Models\PostReaction;
use Modules\Blog\Models\ReactionType;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);

        $userArr    = User::take(20)->pluck('id')->toArray();
        $userArrLen = count($userArr);
        $userArrMax = $userArrLen - 1;

        Post::factory()
            ->count(50)
            ->create([
                'user_id' => $userArr[rand(1, $userArrMax)],
            ]);

        $reactionTypeArr    = ReactionType::pluck('id')->toArray();
        $reactionTypeArrLen = count($reactionTypeArr);
        $max                = $reactionTypeArrLen - 1;
        $latest_posts       = Post::orderBy('id', 'DESC')->take(5)->get();

        foreach ($latest_posts as $key => $post) {
            PostReaction::create([
                'post_id'          => $post->id,
                'user_id'          => $userArr[rand(1, $userArrMax)],
                'reaction_type_id' => $reactionTypeArr[rand(1, $max)],
            ]);
        }
    }
}

<?php
namespace Modules\Blog\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Blog\Models\ReactionType;

class ReactionTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ReactionType::create(['name' => 'Like', 'icon' => 'icons/like.png', 'reacted_icon' => 'icons/liked.png', 'order' => 1, 'is_default' => 1]);
        ReactionType::create(['name' => 'Love', 'icon' => 'icons/love.png', 'reacted_icon' => 'icons/loved.png', 'order' => 2, 'is_default' => 0]);
        ReactionType::create(['name' => 'Angry', 'icon' => 'icons/angry.png', 'reacted_icon' => 'icons/angried.png', 'order' => 3, 'is_default' => 0]);
    }
}

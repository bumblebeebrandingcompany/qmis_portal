<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    public function run()
    {
        // You can customize the tags you want to add
        $tags = ['Intrested', 'Not Intrested', 'RNR'];

        foreach ($tags as $tagName) {
            Tag::create(['name' => $tagName]);
        }
    }
}


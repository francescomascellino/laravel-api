<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Faker $faker): void
    {
        $types = Type::pluck('id'); // The pluck method retrieves all of the values for a given key:
        
        for ($i = 0; $i < 10; $i++) {
            $project = new Project();

            $project->type_id = $faker->randomElement($types);
            $project->title = $faker->realText(50);
            $project->slug = Str::slug($project->title, '-');
            $project->thumb = 'thumbs/' . $faker->image('public/storage/thumbs', 600, 480, 'Project', false);
            // $project->thumb = $faker->imageUrl(category: 'Projects');
            $project->description = $faker->realText();
            // $project->tech = $faker->company();
            $project->github = $faker->url();
            $project->link = $faker->url();

            $project->save();
        }
    }
}

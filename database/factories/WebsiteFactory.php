<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Website;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Website>
 */
class WebsiteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userIds = User::pluck('id');
        return [
            'name' => fake()->domainName(),
            'user_id' => fake()->randomElement($userIds)
        ];
        
    }

    public function configure()
    {
        return $this->afterCreating(function (Website $website) {
            // Attach a category to the website
            $category = Category::inRandomOrder()->first();
            $website->categories()->attach($category);
        });
    }
}

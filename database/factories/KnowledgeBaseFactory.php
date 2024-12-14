<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class KnowledgeBaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'department_id' => 1,
            'content' => $this->faker->text,
            'user_id' => 1,
            'status' => 1,
        ];
    }

}

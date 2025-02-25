<?php

namespace Database\Factories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition()
    {
        return [
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'phone2' => $this->faker->optional()->phoneNumber,
            'address' => $this->faker->address,
            'facebook' => $this->faker->optional()->url,
            'instagram' => $this->faker->optional()->url,
            'telegram' => $this->faker->optional()->url,
            'youtube' => $this->faker->optional()->url,
            'tik_tok' => $this->faker->optional()->url,
        ];
    }
}

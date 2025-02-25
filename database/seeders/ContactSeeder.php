<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Contact::create([
            'email' => 'example@example.com',
            'phone' => '+998901234567',
            'address' => 'Tashkent, Uzbekistan',
            'facebook' => 'https://facebook.com/example',
            'instagram' => 'https://instagram.com/example',
            'telegram' => 'https://t.me/example',
            'youtube' => 'https://youtube.com/example',
            'linkedin' => 'https://linkedin.com/in/example',
        ]);
    }
}

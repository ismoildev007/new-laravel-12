<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    use RefreshDatabase; // Test uchun yangi ma'lumotlar bazasi yaratadi

    public function test_index_displays_contacts()
    {
        $user = User::factory()->create(); // Foydalanuvchi yaratish
        $this->actingAs($user); // Foydalanuvchini autentifikatsiya qilish

        Contact::factory()->count(3)->create(); // 3 ta kontakt yaratish

        $response = $this->get(route('contacts.index')); // Route orqali so'rov yuborish

        $response->assertStatus(200);
        $response->assertViewIs('contacts.index');
        $response->assertViewHas('contacts'); // Blade faylda 'contacts' o'zgaruvchisi borligini tekshirish
    }

    public function test_edit_displays_contact_edit_page()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $contact = Contact::factory()->create(); // Test uchun kontakt yaratish

        $response = $this->get(route('contacts.edit', $contact));

        $response->assertStatus(200);
        $response->assertViewIs('contacts.edit');
        $response->assertViewHas('contact', $contact);
    }

    public function test_update_validates_and_updates_contact()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $contact = Contact::factory()->create([
            'email' => 'oldemail@example.com',
        ]);

        $updateData = [
            'email' => 'newemail@example.com',
            'phone' => '998901234567',
            'phone2' => '998907654321',
            'address' => 'Tashkent, Uzbekistan',
            'facebook' => 'https://facebook.com/test',
            'instagram' => 'https://instagram.com/test',
            'telegram' => 'https://t.me/test',
            'youtube' => 'https://youtube.com/test',
            'tik_tok' => 'https://tiktok.com/@test',
        ];

        $response = $this->put(route('contacts.update', $contact), $updateData);

        $response->assertRedirect(); // Yuborilgan so‘rov muvaffaqiyatli bo‘lsa, qayta yo‘naltiriladi
        $response->assertSessionHas('success', 'Contact successfully updated!');

        $this->assertDatabaseHas('contacts', ['email' => 'newemail@example.com']); // Baza yangilanganini tekshirish
    }

    public function test_update_fails_with_invalid_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $contact = Contact::factory()->create();

        $invalidData = [
            'email' => 'not-an-email', // Noto'g'ri email
            'phone' => str_repeat('a', 30), // Juda uzun telefon raqam
            'facebook' => 'not-a-url', // Noto'g'ri URL
        ];

        $response = $this->put(route('contacts.update', $contact), $invalidData);

        $response->assertSessionHasErrors(['email', 'phone', 'facebook']);
    }
}

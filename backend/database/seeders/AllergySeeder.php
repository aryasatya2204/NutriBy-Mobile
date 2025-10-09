<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AllergenGroup;
use App\Models\AllergyFact;

class AllergySeeder extends Seeder
{
    public function run(): void
    {
        $egg = AllergenGroup::updateOrCreate(['code' => 'egg'], ['name' => 'Telur', 'image_url' => 'url/gambar/telur.png']);
        $milk = AllergenGroup::updateOrCreate(['code' => 'milk'], ['name' => 'Susu Sapi', 'image_url' => 'url/gambar/susu.png']);

        AllergyFact::updateOrCreate(
            ['allergen_group_id' => $egg->id, 'title' => 'Fakta Alergi Telur'],
            [
                'content' => 'Alergi telur adalah salah satu alergi makanan yang paling umum pada anak-anak. Pencegahan terbaik adalah menghindari semua produk yang mengandung telur.',
                'symptoms' => 'Ruam merah, gatal-gatal, bengkak di wajah, muntah, diare.',
                'triggers' => 'Putih telur, kuning telur, mayones, kue yang mengandung telur.'
            ]
        );

        AllergyFact::updateOrCreate(
            ['allergen_group_id' => $milk->id, 'title' => 'Fakta Alergi Susu Sapi'],
            [
                'content' => 'Alergi susu sapi terjadi ketika sistem kekebalan tubuh bereaksi berlebihan terhadap protein dalam susu. Gunakan susu formula hipoalergenik sebagai alternatif.',
                'symptoms' => 'Eksim, kulit kemerahan, sakit perut, diare, sulit bernapas.',
                'triggers' => 'Susu formula, keju, yoghurt, mentega, biskuit susu.'
            ]
        );
    }
}
<?php

use Illuminate\Database\Seeder;
use App\Sponsorship;

class SponsorshipsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sponsorships = [
            [
                'title' => 'Silver',
                'price' => 2.99,
                'duration' => 24
            ],
            [
                'title' => 'Gold',
                'price' => 5.99,
                'duration' => 72
            ],
            [
                'title' => 'Platinum',
                'price' => 9.99,
                'duration' => 144
            ]
        ];

        foreach($sponsorships as $sponsorship){
            $newSponsorship = new Sponsorship();
            $newSponsorship->title = $sponsorship['title'];
            $newSponsorship->price = $sponsorship['price'];
            $newSponsorship->duration = $sponsorship['duration'];
            $newSponsorship->save();
        }

    }
}

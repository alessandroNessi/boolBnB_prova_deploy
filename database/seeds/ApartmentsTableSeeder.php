<?php

use Illuminate\Database\Seeder;
use App\Apartment;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

class ApartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $addresses = ["Via Appia", "Via Cassia", "Via Condotti", "Via del Corso", "Via di Ripetta", "Via Flaminia", "Via Frattina", "Via Nazionale", "Via Tuscolana", "Via Veneto"];

        for( $i=0 ; $i<10 ; $i++ ){
            $newApartment = new Apartment();
            $newApartment -> user_id = $faker->numberBetween(1, 2);
            $newApartment ->title = $faker->words(10, true);
            $newApartment ->rooms = $faker->numberBetween(1, 20);
            $newApartment ->guests_number = $faker->numberBetween(1, 20);
            $newApartment ->bathrooms = $faker->numberBetween(1, 10);
            $newApartment ->sqm = $faker->numberBetween(40, 1000);
            $newApartment ->region = "Italy";
            $newApartment ->city = "Rome";
            $newApartment ->address = $addresses[$i];
            $newApartment ->number = $faker->numberBetween(1, 200);
            $newApartment ->latitude = $faker->randomFloat($nbMaxDecimals = 8, $min = 41.74219900, $max = 42.23658900);
            $newApartment ->longitude = $faker->randomFloat($nbMaxDecimals = 8, $min = 12.24396800, $max = 12.93338000);
            $newApartment ->cover = "https://picsum.photos/600/400";
            $newApartment ->visibility = $faker->boolean();
            $newApartment ->slug =Str::of($newApartment->title)->slug('-');
            $newApartment ->description = $faker->text(100);
            $newApartment -> save();
        }

    }
}

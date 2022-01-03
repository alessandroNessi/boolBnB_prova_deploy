<?php

use Illuminate\Database\Seeder;
use App\Service;

class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $services = ['cucina','riscaldamento','tv','aria condizionata','piscina','wi-fi','lavatrice','colazione','bagno privato'];

        foreach($services as $service){
            $newService = new Service();
            $newService->name= $service;
            $newService->save();
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Service;
use App\Apartment;

class ServiceController extends Controller
{
    /**
     * @return all services avaible
     */
    public function index()
    {
        $services = Service::all();
        return response()->json([           
            'success' => true,
            'data' => $services
        ]);
    }

    /**
     * Given the @param  service id @return the corresponding record
     */
    public function getService($id)
    {
        $service=Service::where('id',$id)->first();
        return response()->json([           
            'success' => true,
            'data' => $service
        ]);
    }
}

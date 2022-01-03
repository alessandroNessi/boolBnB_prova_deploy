<?php

namespace App\Http\Controllers\Admin;

use App\Apartment;
use DateTime;
use App\Message;
use App\Statistic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StatisticController extends Controller
{
    public function index($id) {
        return view('admin.statistics.index', compact('id'));
    }

    public function show($id, $month) {
        $user = Auth::user();
        $apartment = Apartment::where('id', '=', $id)->first();
        
        if($user->id != $apartment->user_id){
            abort(404);
        }

        $statistics = Statistic::all()->where('apartment_id', '=', $id);
        $messages = Message::all()->where('apartment_id', '=', $id);
        
        if($month == 'all') {
            $data = [];
            $mailbox = [];
            for($i = 1; $i <= 12; $i++){
                $data[$i] = [];
                $mailbox[$i] = [];

                foreach($statistics as $statistic){
                    $date = new DateTime($statistic->created_at);
                    if($date->format('n') == $i) {
                        array_push($data[$i], $statistic);
                    }
                }

                foreach($messages as $message){
                    $date = new DateTime($message->created_at);
                    if($date->format('n') == $i) {
                        array_push($mailbox[$i], $message);
                    }
                }
            }
            return response()->json([           
                'success' => true,
                'data' => [
                    'views' => $data,
                    'messages' => $mailbox
                ]
            ]);
        } else {
            $data = [
                'Q1' => [],
                'Q2' => [],
                'Q3' => [],
                'Q4' => []
            ];
            $mailbox = [
                'Q1' => [],
                'Q2' => [],
                'Q3' => [],
                'Q4' => []
            ];

            foreach($statistics as $statistic) {
                $date = new DateTime($statistic->created_at);
                if($date->format('m') == $month){
                    if(1 <= $date->format('j') && $date->format('j') < 8){
                        array_push($data['Q1'], $statistic);
                    } elseif(8 <= $date->format('j') && $date->format('j') < 15){
                        array_push($data['Q2'], $statistic);
                    } elseif(15 <= $date->format('j') && $date->format('j') < 22){
                        array_push($data['Q3'], $statistic);
                    } else {
                        array_push($data['Q4'], $statistic);
                    }
                }
            }

            foreach($messages as $message) {
                $date = new DateTime($message->created_at);
                if($date->format('m') == $month){
                    if(1 <= $date->format('j') && $date->format('j') < 8){
                        array_push($mailbox['Q1'], $message);
                    } elseif(8 <= $date->format('j') && $date->format('j') < 15){
                        array_push($mailbox['Q2'], $message);
                    } elseif(15 <= $date->format('j') && $date->format('j') < 22){
                        array_push($mailbox['Q3'], $message);
                    } else {
                        array_push($mailbox['Q4'], $message);
                    }
                }
            }

            return response()->json([           
                'success' => true,
                'data' => [
                    'views' => $data,
                    'messages' => $mailbox
                ]
            ]);
        }
    }
}

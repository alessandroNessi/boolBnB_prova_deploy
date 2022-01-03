<?php

namespace App\Http\Controllers\Admin;

use App\Apartment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ImageController extends Controller
{
    protected $validationRules = [
        "url" => "required|mimes:jpeg,jpg,png|max:1024",
        "apartment_id" => "nullable|exists:apartment,id"
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("admin.images.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $apartment = Apartment::all()->where('id', '=', $id)->first();

        if($apartment->user_id != Auth::user()->id) {
            abort(404);
        }
        
        return view("admin.images.create", compact('apartment'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {   
        $request->validate($this->validationRules);
        $apartment = Apartment::all()->where('id', '=', $id)->first();

        if($apartment->user_id != Auth::user()->id) {
            abort(404);
        }

        $newImage= new Image;
        $newImage-> url = Storage::put("images_url",$request->url);
        $newImage-> apartment_id = $id;
        $newImage->save();

        return redirect()->route("admin.images.create", $id)->with('success', "Immagine aggiunta all'album");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $apartment = Apartment::all()->where('id', '=', $id);

        // dd($apartment);

        // return view('admin.images.show', compact('apartment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $image = Image::find($id);
        $image->delete();

        $apartment = Apartment::all()->where('id', '=', $image->apartment_id)->first();

        return redirect("admin/images/create/{$apartment->id}")->with('success','Immagine Eliminata');
    }
}


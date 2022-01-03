<?php

namespace App\Http\Controllers\Admin;

use App\Apartment;
use App\Sponsorship;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Service;
use App\Message;
use Mockery\Undefined;

class ApartmentController extends Controller
{
    /*validation rules*/
    protected $validationRules = [
        'title' => 'required|string|max:100|min:5',
        'rooms' => 'required|integer|min:1|max:255',
        'guests_number' => 'required|integer|min:1|max:255',
        'bathrooms' => 'required|integer|min:1|max:255',
        'sqm' => 'nullable|integer|min:1|max:1000',
        'address' => 'required|string|max:900|min:10',
        'cover' => 'nullable|mimes:jpeg,jpg,png|max:1024',
        'description' => 'nullable|string|max:10000'
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $user_id = Auth::user()->id;

        $apartments = Apartment::all()->where('user_id', $user_id);

        return view('admin.apartments.index', compact('apartments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $services = Service::all();

        
        return view('admin.apartments.create',compact("services"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->validationRules);
        $newApartment = new Apartment();
        // slug, latitude, longitude, visibility, user_id
        if($request->cover != null){
            $newApartment->cover = Storage::put('apartments_cover', $request->cover);
        }

        $newApartment->fill($request->all());
        //chiamata guzzle
        $client = new Client([ 'base_uri' => 'https://api.tomtom.com/search/2/geocode/', 'timeout'  => 2.0, 'verify' => false]); 
        $response = $client->get($request->address.'.json?key=lXA4qKasPyxqJxup4ikKlTFOL3Z89cp4');
        $results = json_decode($response->getBody());
        $results = $results->results;
        $this->checkLocation($results,$request);
        //assegnazione parametri ottenuti da guzzle
        isset($results[0]->address->streetNumber) ? $newApartment->number = $results[0]->address->streetNumber:$newApartment->number =0;
        //numero civico 0 significa indirizzo senza numero civico (SNC)
        $newApartment->region = strtolower($results[0]->address->country);
        $newApartment->address = strtolower($results[0]->address->streetName);
        $newApartment->city = strtolower($results[0]->address->municipality);
        $newApartment->latitude = $results[0]->position->lat;
        $newApartment->longitude = $results[0]->position->lon;
        
        //assegnazione ultimi parametri mancanti
        $newApartment->slug = $this->getSlug($newApartment->title);
        $newApartment->visibility = true;
        $newApartment->user_id = Auth::user()->id;
        
        $newApartment->save();
         
        $newApartment->services()->attach($request["services"]);

        return redirect()->route('admin.apartments.index')->with('success','Aggiunto appartamento');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Apartment $apartment)
    {   
        $sponsorships = Sponsorship::all();
        $services = Service::all();
        $messages = Message::all()->where('apartment_id', '=', $apartment->id);
        return view('admin.apartments.show', compact('apartment', 'services', 'messages', 'sponsorships'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Apartment $apartment)
    {

        $this->checkLoggedUser($apartment);

        $services = Service::all();
        $images = $apartment->images;
        
        return view('admin.apartments.edit', compact('apartment','services', 'images'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Apartment $apartment)
    {
        $request->validate($this->validationRules);

        //controllo cover appartamento ed eventuale delete vecchia cover e salvataggio della nuova
        if(array_key_exists('cover', $request->all())){
            if($apartment->cover){
                Storage::delete($apartment->cover);
            }
            $apartment->cover = Storage::put('apartments_cover', $request->cover);
        }

        $apartment->fill($request->all());

        //cambio visibility
        if(array_key_exists('visibility', $request->all())&&$request->visibility=='on'){
            $apartment->visibility=1;
        }else{
            $apartment->visibility=0;
        }

        //chiamata guzzle
        $client = new Client([ 'base_uri' => 'https://api.tomtom.com/search/2/geocode/', 'timeout'  => 2.0, 'verify' => false]); 
        $response = $client->get($request->address.'.json?key=lXA4qKasPyxqJxup4ikKlTFOL3Z89cp4');
        $results = json_decode($response->getBody());
        $results = $results->results;
        $this->checkLocation($results,$request);
        //assegnazione parametri ottenuti da guzzle
        isset($results[0]->address->streetNumber) ? $apartment->number = $results[0]->address->streetNumber:$apartment->number =0;
        //numero civico 0 significa indirizzo senza numero civico (SNC)
        $apartment->region = strtolower($results[0]->address->country);
        $apartment->address = strtolower($results[0]->address->streetName);
        $apartment->city = strtolower($results[0]->address->municipality);
        $apartment->latitude = $results[0]->position->lat;
        $apartment->longitude = $results[0]->position->lon;

        //assegnazione ultimi parametri
        $apartment->slug = $this->getSlug($apartment->title);
        $apartment->user_id = Auth::user()->id;

        $apartment->save();
        $apartment->services()->sync($request->services);

        return redirect()->route('admin.apartments.index')->with('success', 'Modifiche effettuate correttamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Apartment $apartment)
    {
        $apartment->delete();

        return redirect('admin/apartments')->with('success','Annuncio Eliminato');
    }

    protected function getSlug($title) 
    {
        // creo lo slug con l'helper partendo dal $title
        $slug = Str::of($title)->slug('-');
        //creo una variabile che avrà un valore diverso da null nel momento in cui il database conterrà una entry la cui voce slug sarà uguale a quella che ho appena creato
        $duplicateSlug = Apartment::where('slug', $slug)->first();
        //inizializzo un contatore che utilizzerò per aggiungere un numero incrementale allo slug nel caso in cui ci fosse un duplicato
        $count = 2;
        //entro in un ciclo while nel caso in cui il valore di $duplicateSlug non sia null
        while($duplicateSlug) {
            // creo un nuovo slug concatenando il valore del count
            $slug = Str::of($title)->slug('-') . "-{$count}";
            //verifico che il nuovo slug non esista
            $duplicateSlug = Apartment::where('slug', $slug)->first();
            //se il nuovo slug non esiste il valore di $duplicateSlug sarà nullo ed uscirò dal ciclo
            //aumento il contatore per far sì che, in caso di slug duplicato, venga assegnato un nuovo valore alla successiva iterazione del ciclo
            $count++;
        }
        // restituisco lo slug
        return $slug;
    }

    protected function inputToLower($array) {
        foreach($array as $key=>$value){
            if($key=="title"||$key=="region"||$key=="city"||$key=="address"){
                $array[$key]=strtolower($value);
            }
        }
        return $array;
    }
    protected function checkLocation($results,$request){
        if(!isset($results[0]->address->streetName)){
            $request->merge([
                'address' => '',
            ]);
            $request->validate($this->validationRules);
        }
    }

    protected function checkLoggedUser($apartment){
        if($apartment->user_id!=Auth::user()->id){
            abort(404);
            return redirect()->route('admin.apartments.index')->with('error', 'Errore, l\'appartamento selezionato non esiste');
        }
    }
}

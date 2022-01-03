@extends('layouts.app')

@section('content')

<div class="container">
    <div>
        <form action="{{route("admin.apartments.update", $apartment->id)}}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            {{-- input per il titolo dell'appartamento --}}
            <div class="mb-3">
                <label for="title" class="form-label"><h5>Titolo</h5></label>
                <input type="text" name="title" class="ms_input pl-4 form-control @error('title') is-invalid @enderror" id="title" value="{{old('title') ?? $apartment->title}}" placeholder="Inserisci il titolo">
                @error('title')
                    <div class="alert alert-danger">{{$message}}</div>
                @enderror
            </div>

            {{-- input per il numero di stanze dell'appartamento --}}
            <div class="mb-3">
                <label for="rooms" class="form-label"><h5>Stanze</h5></label>
                <input type="number" name="rooms" class="ms_input pl-4 form-control @error('rooms') is-invalid @enderror" id="rooms" value="{{old('rooms') ?? $apartment->rooms}}" placeholder="Seleziona il numero di stanze">
                @error('rooms')
                    <div class="alert alert-danger">{{$message}}</div>
                @enderror
            </div>

            {{-- input per posti letto dell'appartamento --}}
            <div class="mb-3">
                <label for="guests_number" class="form-label"><h5>Posti letto</h5></label>
                <input type="number" name="guests_number" class="ms_input pl-4 form-control @error('guests_number') is-invalid @enderror" id="guests_number" value="{{old('guests_number') ?? $apartment->guests_number}}" placeholder="Posti letto">
                @error('guests_number')
                    <div class="alert alert-danger">{{$message}}</div>
                @enderror
            </div>

            {{-- input per il numero di bagni dell'appartamento --}}
            <div class="mb-3">
                <label for="bathrooms" class="form-label"><h5>Bagni</h5></label>
                <input type="number" name="bathrooms" class="ms_input pl-4 form-control @error('bathrooms') is-invalid @enderror" id="bathrooms" value="{{old('bathrooms') ?? $apartment->bathrooms}}" placeholder="Bagni">
                @error('bathrooms')
                    <div class="alert alert-danger">{{$message}}</div>
                @enderror
            </div>

            {{-- input per l'estensione dell'appartamento --}}
            <div class="mb-3">
                <label for="sqm" class="form-label"><h5>Metri quadrati</h5></label>
                <input type="number" name="sqm" class="ms_input pl-4 form-control @error('sqm') is-invalid @enderror" id="sqm" value="{{old('sqm') ?? $apartment->sqm}}" placeholder="Seleziona l'estensione">
                @error('sqm')
                    <div class="alert alert-danger">{{$message}}</div>
                @enderror
            </div>

            {{-- input per l'indirizzo dell'appartamento --}}
            <div class="mb-3">
                <label for="addressoptions" class="form-label"><h5> Indirizzo</h5></label>
                <input list="addressoptions" type="text" name="address" class="ms_input pl-4 form-control @error('address') is-invalid @enderror" id="address" value="{{ old('address') ?? $apartment->address }}" placeholder="Es: Milano, corso como 10">
                <datalist name="addressoptions" id="addressoptions" class="ml-4">
                    <option>{{old('addressoption') ?? $apartment->address}}</option>
                </datalist>
                @error('address')
                    <div class="alert alert-danger">{{$message}}</div>
                @enderror
            </div>
            {{-- <div class="mb-3">
                <label for="addressoptions" class="form-label"><h5>Indirizzo</h5></label>
                <input type="search" name="addressoptions" class="ms_input pl-4 form-control @error('addressoptions') is-invalid @enderror" id="addressoptions" value="{{ old('addressoptions') ?? $apartment->address }}" placeholder="Es: Milano, corso como 10">
                <select name="address" id="address" class="ml-4">
                    <option>
                        {{old('address') ?? $apartment->address}}
                    </option>
                </select>
                @error('address')
                    <div class="alert alert-danger">{{$message}}</div>
                @enderror
            </div> --}}
            
            {{-- Old Cover --}}
            @if ($apartment->cover)
                <h6>Anteprima copertina</h6>
                <img width="100px" src="{{asset('storage/' . $apartment->cover)}}" alt="{{$apartment->title}}" class="mb-2 mt-2">                                              
            @endif

            {{-- input per l'immagine di copertina dell'appartamento --}}
            <div class="mb-3">
                <label for="cover" class="form-label"><h5>Modifica copertina</h5></label>
                <input type="file" name="cover" class="ms_input pl-3 form-control ms_pb_4 @error('cover') is-invalid @enderror" id="cover" value="{{old('cover') ?? $apartment->cover}}">
                @error('cover')
                    <div class="alert alert-danger">{{$message}}</div>
                @enderror
            </div>

            
            {{-- VISUALIZZAZIONE IMMAGINI AGGIUNTIVE --}}
            <h6>Galleria Immagini</h6>
            @if(count($images) > 0)
            <div class="mb-3">
                @foreach ($images as $image)
                    <img width="100px" src="{{asset('storage/' . $image->url)}}" alt="{{$apartment->title}}" class="mb-2 mt-2">                                              
                @endforeach
            </div>
            @else
            <div class="mb-3">
            --> L'album non contiene nessuna immagine <--
            </div>
            @endif
            

            {{-- immagini aggiuntive --}}
            <button type="button " class="btn ms-btn_white mb-4">
                    <a class="ms_a" href="{{route('admin.images.create', $apartment->id)}}">
                    Aggiungi altre immagini
                    </a>
            </button>


            {{-- input toogle per la visibilità --}}
            <div class="mb-3">
                <div class="custom-control custom-switch">
                    <input class="custom-control-input" type="checkbox" id="visibility" name="visibility" {{old('visibility') ? 'checked' : ($apartment->visibility ? 'checked':'')}}/>
                    <label class="custom-control-label" for="visibility">Visibilità</label>
                </div>
            </div>
                
    
            {{-- input per la descrizione dell'appartamento --}} 
            <div class="mb-3">
                <label for="description" class="form-label"><h5>Descrizione</h5></label>
                <textarea name="description" id="description" class="ms_input p-4 form-control @error('description') is-invalid @enderror" cols="30" rows="10" placeholder="Inserisci una descrizione. ">{{old('description') ?? $apartment->description}}</textarea>
                @error('description')
                    <div class="alert alert-danger">{{$message}}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <h5 class="pt-2">Servizi</h5>
                @foreach ($services as $service)
                    <div class="custom-control custom-checkbox">
                        @if ($errors->any())
                        <input {{in_array($service['id'], old("services", [])) ? "checked" : null}} name="services[]" value="{{$service['id']}}" type="checkbox" class="custom-control-input" id="service-{{$service['id']}}">
                        @else
                        <input {{$apartment["services"]->contains($service["id"]) ? "checked" : null}} name="services[]" value="{{$service['id']}}" type="checkbox" class="custom-control-input" id="service-{{$service['id']}}">
                        @endif
                        <label class="custom-control-label" for="service-{{$service['id']}}">{{$service['name']}}</label>
                    </div>
                @endforeach
            </div>

            <button type="submit" class="btn ms-btn_blu mt-3 mb-3 mr-2">Pubblica modifiche</button>
           
            <button type="button" class="btn ms-button" data-toggle="modal" data-target="#deleteModal">
                Elimina annuncio
            </button>
        </form>
    </div>
</div>
    
        <!-- Modal -->
        <div class="modal fade" id="deleteModal">
            <form action="{{route('admin.apartments.destroy', $apartment->id)}}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Conferma Cancellazione Annuncio</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Vuoi cancellare definitivamente questo annuncio?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                            <button type="submit" class="btn btn-primary">Si</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    
    <script src="{{asset('js/prova.js')}}"></script>
@endsection


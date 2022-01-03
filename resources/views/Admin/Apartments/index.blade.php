@extends('layouts.app')

@section('content')

<div class="container col-10">
    <table class="table">
        <h1 class="mb-4">I Tuoi Appartamenti</h1>
        <a href="{{route("admin.apartments.create")}}">
            <button type="button" class="btn ms-button mt-2 mb-3">Aggiungi un nuovo appartamento</button>
        </a>
    </table> 
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
        </div>
    @endif
    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
        </div>
    @endif   
        <table class="table apartment_index ms_capitalize">
            <thead >
                <tr >
                    <th scope="col">Annuncio</th>
                    <th scope="col">Titolo</th>
                    <th scope="col">Indirizzo</th>
                    <th scope="col">N° civico</th>
                    <th scope="col">Cosa vuoi fare?</th>
                </tr>
            </thead>
            @if (count($apartments) > 0)
            <tbody class="mt-3 ">
                @foreach ($apartments as $apartment)
                    @if ($apartment->visibility == 0)
                    <tr class="ms_fontweight ms_invisible">
                    @else
                    <tr class="ms_fontweight">
                    @endif
                        <td class="ms_lightblue">{{$apartment->id}}</td>
                        <td class="ms_lightblue">{{$apartment->title}}</td>
                        <td class="capitalize ms_lightblue">{{$apartment->address}}</td>
                        <td class="ms_lightblue">{{$apartment->number}}</td>
                        <td>
                                <button type="button" class="btn ms-btn_white">
                                    <a class="ms_a" href="{{route("admin.apartments.update", $apartment['id'])}}">Visualizza</a>
                                </button>
                            <a href="{{route("admin.apartments.edit",$apartment['id'])}}">
                                <button type="button" class="btn ms-btn_light">Modifica</button>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        @else
        <tbody>
            <tr>
              <td class="pt-5"><a  class="ms_a ms_orange" href="{{route("admin.apartments.create")}}"><h3 class=""> Pubblica il tuo primo annuncio!</h3></a></td>
            </tr>
          </tbody>
        @endif
        </table>
    
    </div>  
@endsection
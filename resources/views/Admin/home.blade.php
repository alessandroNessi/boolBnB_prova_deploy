@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card pl-4 pb-3 mt-5 ms_card">
                

                <div class="card-body  pt-4 ">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                   <h2 class="ms_capitalize ms_orange">Benvenut*  {{ Auth::user()->first_name }} !</h2>
                   <h4 class="ms_orange">Cosa vuoi fare?</h4>
                </div>
                <div class="card-body">
                    <a href="{{route("admin.apartments.index")}}" class="ms_a ms_fontweight">Visualizza i tuoi appartamenti</a>
                </div>
                <div class="card-body">
                    <a href="{{ route("admin.users.edit") }}" class="ms_a ms_fontweight">Modifica il tuo profilo</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

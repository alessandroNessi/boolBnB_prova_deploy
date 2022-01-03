@extends('layouts.app')

@section('content')
<div class="container col-7">
        <form action="{{route("admin.users.update", $user->id)}}" method="POST">
            @method('PUT')
            @csrf
            {{-- input per modificare il nome --}}
            <div class="mb-3">
                <label for="first_name" class="form-label"><h5> Nome *</h5></label>
                <input type="text" name="first_name" class="ms_input pl-4 form-control @error('first_name') is-invalid @enderror" id="first_name" value="{{old('first_name') ?? $user->first_name}}" placeholder="Inserisci il tuo nome">
                @error('first_name')
                    <div class="alert alert-danger">{{$message}}</div>
                @enderror
            </div>
            {{-- input per modificare il cognome --}}
            <div class="mb-3">
                <label for="last_name" class="form-label"><h5>Cognome *</h5></label>
                <input type="text" name="last_name" class="ms_input pl-4 form-control @error('last_name') is-invalid @enderror" id="last_name" value="{{old('last_name') ?? $user->last_name}}" placeholder="Inserisci il tuo cognome">
                @error('last_name')
                    <div class="alert alert-danger">{{$message}}</div>
                @enderror
            </div>

            {{-- input per modificare il email --}}
            <div class="mb-3">
                <label for="email" class="form-label"><h5>Email</h5>(non modificabile)</label>
                <input type="text" name="email" class="ms_input pl-4 form-control" id="email" value="{{old('email') ?? $user->email}}" disabled>
                
            </div>

            <div class="mb-3">
                <label for="date_of_birth" class="form-label"><h5>Data di nascita *</h5></label>
                <input type="date" name="date_of_birth" class="ms_input pl-4 form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth" value="{{old('date_of_birth') ?? $user->date_of_birth}}">
                @error('date_of_birth')
                    <div class="alert alert-danger">{{$message}}</div>
                @enderror
            </div>

           <div class="pb-4">* campi obbligatori</div>
            <button type="submit" class="btn ms-btn_light mr-2">Pubblica</button>
            <a class="btn ms-btn_blu mr-2" href="{{ url('/admin/apartments') }}">
                Annulla
            </a>
            <button type="button" class="btn ms-button mr-2" data-toggle="modal" data-target="#deleteModal">
                Elimina profilo
            </button>
            
        </form>
        <!-- Modal -->
        <div class="modal fade" id="deleteModal">
            <form action="{{route('admin.users.destroy', $user->id)}}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Conferma Cancellazione Utente</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Vuoi cancellare definitivamente il tuo profilo?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                            <button type="submit" class="btn btn-primary">Si</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <script src="{{asset('js/app.js')}}"></script>
    
    </div>
@endsection
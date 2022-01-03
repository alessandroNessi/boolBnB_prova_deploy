@extends('layouts.app')

@section('content')
    <script>
        window.Apartment = {!!json_encode([
            'id' => $id
        ])!!}
    </script>
    
    <div id="chart">

    </div>
    <script src="{{asset('js/statistics.js')}}"></script>
@endsection
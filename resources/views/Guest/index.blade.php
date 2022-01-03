<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{asset('css/front.css')}}">
        <title>BoolBNB</title>
    </head>
    <body>
        @php            
            if (Session::get('success') != null ){
                $message = Session::get('success');
            }
        @endphp

        @if (Session::get('success') != null )
            <script>
                window.Redirect = {!!json_encode([
                'success' => $message
            ])!!}
            </script>
        @endif
        
        @if (Auth::check())
            <script>
                window.Laravel = {!!json_encode([
                    'isLoggedin' => true,
                    'user' => Auth::user()
                ])!!}
            </script>
        @else
            <script>
                window.Laravel = {!!json_encode([
                    'isLoggedin' => false
                ])!!}
            </script>
        @endif
        <div id="app">

        </div>
        <script src="{{asset('js/front.js')}}"></script>
    </body>
</html>
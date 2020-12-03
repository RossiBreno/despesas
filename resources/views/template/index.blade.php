<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/template.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="{{ URL::asset('images/fav.ico') }}" type="image/x-icon">
    <title>@yield('title')</title>
</head>
<body> 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
    <script src="{{ URL::asset('js/mask.js') }}"></script>


    @yield('content')

    
    <script>
        $('#input-file').change(function() {
            if(this.files && this.files.length > 0){
                $('#label-input-file').text(this.files[0].name)
            }
        })
    </script>   
    <script>
        $('#desc').on('input', function(){
            const length = $('#desc').val().length
            $('#carac-length').text(length+'/500 caracteres')
            if(length > 500){
                $('#carac-length').css('color', 'red')
            }else{
                $('#carac-length').css('color', 'black')
            }
        });
    </script>
</body>
</html>
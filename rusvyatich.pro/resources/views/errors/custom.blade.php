<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/index.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/normalize.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700&subset=cyrillic" rel="stylesheet">
    <title>Повторите позже</title>
</head>
    <body class='constructOrderBody allUsersBody'>   
        <main>    
            @include('layouts.brandHead')
            <div class="container">
                <div class="serverError">
                    Кажется что-то пошло не так. Пожалуйста обновите страницу или попробуйте позже.
                </div>
            </div>
        </main>
        <footer>
        </footer>
        <div id="flag900"></div>
        <div id="flag600"></div>
        <div id="flag400"></div>
        <div id="flag1000"></div>
        <div id="flag1200"></div>
    </body>
<script src="{{ asset('admin/js/jquery.min.js') }}"></script>
<script src="{{ asset('admin/js/admin.js') }}" type="text/javascript"></script>
</html>

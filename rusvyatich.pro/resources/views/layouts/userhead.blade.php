<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @if(!empty($descriptionPage))<meta name="description" content="{{$descriptionPage}}" />@endif
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/css/index.css') }}?{{VERSION}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/css/normalize.css') }}?{{VERSION}}">
    <title>{{ $title }}</title>
</head>

@yield('content')
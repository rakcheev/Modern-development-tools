<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="interkassa-verification" content="bb9e953f86d8af8ec3c7b5a73f5ab7d3" />
    @if(!empty($descriptionPage))<meta name="description" content="{{$descriptionPage}}" />@endif
    <link rel="stylesheet" type="text/css" href="{{ asset('css/index.css') }}?{{VERSION}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/normalize.css') }}?{{VERSION}}">
    <title>{{ $title }}</title>
</head>

@yield('content')
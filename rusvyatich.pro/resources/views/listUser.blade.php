<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title></title>
</head>
<body>
@foreach($users as $user)
    <a href="{{route('showUser', [$user->id])}}">Пользователь #{{$user->id}}</a>
@endforeach
</body>
</html>
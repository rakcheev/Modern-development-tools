<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title></title>
</head>
<body>
<span>{{$user->last_visit}}</span>
<span>{{$user->count_visit}}</span>
<span>{{$user->user_agent}}</span>
@foreach($informations as $info)
    {{$info->orientation}}<br/>
    {{$info->battery}}<br/>
    {{$info->date}}<br/>
    {{$info->light}}<br/>
@endforeach
</body>
</html>
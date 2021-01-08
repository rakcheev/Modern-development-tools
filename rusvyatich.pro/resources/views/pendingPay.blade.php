@extends('layouts.site')

@section('content')
    <body>
        <main class=""> 
            @include('layouts.brandHead')
            <div class="container">
           Ожидание оплаты
            </div>
        </main>
        @include('handleOldToken')   
        @include('layouts.footerBig')   
</body>
<script src="{{ asset('js/jquery.min.js') }}?{{VERSION}}"></script>
<script src="{{ asset('js/main.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('js/jquery.maskedinput.min.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('js/jquery.nicescroll.min.js') }}?{{VERSION}}" type="text/javascript"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script type="text/javascript">
    $().ready(function(){
        @include('scripts.closeMessages')
        @include('scripts.sameScripts')
    });
</script>
@endsection
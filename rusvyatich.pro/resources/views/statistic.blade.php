@extends('layouts.userhead')

@section('content')
    <body>
        <main> 
            @include('adminLeftColumn')  
            <div class="statistic">
            </div>
        </main>
        @include('handleOldToken')        
    <footer>
    </footer>
</body>
<script src="{{ asset('admin/js/jquery.min.js') }}?{{VERSION}}"></script>
<script src="{{ asset('admin/js/admin.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('admin/js/jquery.nicescroll.min.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('admin/js/device.js') }}?{{VERSION}}" type="text/javascript"></script>
<script type="text/javascript">
    $().ready(function(){
        @include('scripts.closeMessages')
        @include('scripts.sameScripts')

        $.each($(".admin_navigation a"), function() {

            if ($(this).attr("href") == "#"){
                $(this).parent().addClass('navPushed');
            }
        });

    });
</script>
@endsection
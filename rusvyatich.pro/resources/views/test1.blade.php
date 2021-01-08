<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title></title>
</head>
<body>
<table>
    <tr>
        <th>Освещенность</th>
        <th>Зарядка</th>
        <th>Ориентация</th>
    </tr>
    <tr>
        <td id="light"></td>
        <td id="battery"></td>
        <td id="orientation"></td>
    </tr>
</table>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script src="/js/jquery.cookie.js"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script>
    $(document).ready(function(){
        if ($.cookie('id') == null) {
            uuid = uuidv4();
            $.cookie('id', uuid, { expires: 1460, path: '/' })
        } else {
            uuid = $.cookie('id');
        }

        $.ajax({
            type:'POST',
            url:"/updateUser",
            data:{
                "uuid" : uuid
            },
            dataType:'json',
            success: function(data){
                console.log(data);
            }
        });

        if ("AmbientLightSensor" in window) {
            try {
                var sensor = new AmbientLightSensor();
                sensor.addEventListener("reading", function (event) {
                    lightUpdater(sensor.illuminance);
                });
                sensor.start();
            } catch (e) {
                console.error(e);
            }
        }

        if ("ondevicelight" in window) {
            function onUpdateDeviceLight(event) {
                lightUpdater(event.value);
            }

            window.addEventListener("devicelight", onUpdateDeviceLight);
        }

        if (window.AmbientLightSensor){
            const sensor = new AmbientLightSensor();
            lightUpdater(sensor.illuminance);
        } else {
            lightUpdater('Не поддерживается');
        }

        window.addEventListener("orientationchange", function() {
            orientationUpdater();
        });

        var interval = setInterval(function() {
            batteryUpdater();
            orientationUpdater()
            var battery = $("#battery").text();
            var orientation= $("#orientation").text();
            var light = $("#light").text();
            if (battery == "" || light == "" || orientation == "") return;
            $.ajax({
                type:'POST',
                url:"/testSave",
                data:{
                    "orientation": orientation,
                    "battery": battery,
                    "light": light,
                    "uuid" : uuid
                },
                dataType:'json',
                success: function(data){
                    console.log(data);
                }
            });
        }, 1000);
    });

    function uuidv4() {
        return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
            (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
    )
    }

    function lightUpdater(illuminance) {
        $("#light").text(illuminance);
    }

    function batteryUpdater(){
        try {
            navigator.getBattery().then(function(battery) {
                $('#battery').text(Math.floor(battery.level*100) + '%');
            });
        } catch {
            $('#battery').text('Не поддерживается');
        }
    }

    function orientationUpdater(){

        if (window.DeviceMotionEvent == undefined) {
            $('#orientation').text('Не поддерживается');
        } else {
            window.orientation == 0 ? $('#orientation').text('Вертикально') : $('#orientation').text('Горизонтально');
        }
    }
</script>
</body>
</html>
@extends('layouts.site')

@section('content')
    <body>
        @include('layouts.brandHead')
        <main class=""> 
            <div class="container">
                <div class="conditions">
                    <p>В соответствии с ГОСТами не являются Холодным оружием ножи, соответствующие ниже приведенным требованиям (хотя бы одному).</p>
                    <div class="conditionSection">
                        <div class="headerCondition">1.Не являются холодным оружием ножи клинки которых не приспособлены для укола.</div>
                        <ul>
                            <li>
                                <span>1.1 Ножи без острия. Острие в данном случае может быть заменено каким либо инструментом (пила, отвертка, зубило), либо быть закруглено.</span>
                            </li>
                            <li>
                                <span>1.2 Ножи с  острием расположенным выше линии обуха более чем на 5мм при длине клинка менее 180мм.</span>
                            </li>
                            <li>
                                <span>1.3 Ножи с острием расположенным выше линии обуха более чем на 10мм при любой длине клинка.</span>
                            </li>
                            <li>
                                <span>1.4 Ножи с вогнутым обухом более чем на 5мм при длине клинка менее 180мм.</span>
                            </li>
                            <li>
                                <span>1.5 Ножи с вогнутым обухом более чем на 10мм при любой длине клинка.</span>
                            </li>
                            <li>
                                <span>1.6 Ножи, на обухе которых, не далее 1/3 расположен крюк для вспарывания шкур.</span>
                            </li>
                            <li>
                                <span>1.7 Ножи, у которых величина прогиба обуха и верхней части рукояти ножа, имеющего форму дуги в виде "коромысла", вверх от условной прямой линии, соединяющей острие клинка и верхнюю оконечность рукояти, превышает 15 мм.</span>
                            </li>
                            <li>
                                <span>1.8 Ножи, с клинком менее 90мм.</span>
                            </li>
                            <li>
                                <span>1.9 Ножи, у которых лезвие и обух сходятся под углом более 70 градусов.</span>
                            </li>
                            <li>
                                <span>1.10 Ножи с шириной обуха более 6мм.</span>
                            </li>
                            <li>
                                <span>1.11 Ножи без заточки.</span>
                            </li>
                        </ul>
                    </div>
                    <div class="conditionSection">
                        <div class="headerCondition">2. Не являются холодным оружием ножи с рукоятью, не обеспечивающей надёжного удержания при уколе.</div>
                        <ul>
                            <li><span>2.1 Ножи с рукоятью менее 70мм.</span></li>
                            <li><span>2.2 Ножи у которых отсутствуют упоры для пальцев (гарды).</span></li>
                            <li><span>2.3 Ножи у которых одиночный (односторонний, или двусторонний в сумме) ограничитель или одиночная подпальцевая выемка меньше 5мм.</span></li>
                            <li><span>2.4 Ножи у которых более одной выемки или ограничителя, их величина должна быть меньше 4мм.</span></li>
                        </ul>
                    </div>
                    <div class="conditionSection">
                        <div class="headerCondition">3. Ножи не обеспечивающие необходимой прочности клинка или всей конструкции.</div>
                        <ul>
                            <li><span>3.1 Ножи с клинками твёрдость которых менее 25HRC.</span></li>
                            <li><span>3.2 Ножи с развитым ограничителем или подпальцевой выемкой, при длине клинка до 150мм., и толщине менее 2.5 мм.</span></li>
                            <li><span>3.3 Ножи с надпиленными клинками.</span></li>
                            <li><span>3.4 Ножи с клинками из не обеспечивающих достаточной для оружия прочности материалов (силумин, алюминий, пластмасса).</span></li>
                            <li><span>3.5 Ножи с надпиленными клинками.</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </main>
        @include('handleOldToken')   
        @include('layouts.footerBig')   
</body>
<script src="{{ asset('js/jquery.min.js') }}?{{VERSION}}"></script>
<script src="{{ asset('js/main.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('js/jquery.nicescroll.min.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('js/jquery.maskedinput.min.js') }}?{{VERSION}}" type="text/javascript"></script>
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
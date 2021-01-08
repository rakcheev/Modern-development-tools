<?php

/*
*
* Скачивание картинки svg заказа 
* ножа из конструктора
*
*
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SpuskInOrder;
use App\Spusk;
use App\AdditionOfBlade;
use App\AdditionInOrder;
use App\KnifePropertiesInOrder;
use App\Order;
use App\Handle;
use Illuminate\Support\Facades\DB;

class DownloadSvgController extends Controller
{
    public function download($pathBlade, $pathBolster, $pathHandle, $bolsterWidth, $handle, $blade, $handleMaterial, $bolster, $steel, $bladeLength, $bladeHeight, $buttWidth, $handleLength, $bladeTransform, $bolsterTransform, $handleTransform, $bladeColor, $bolsterColor, $handleColor, $bolsterWrapTransform, $bladeWrapTransform, $handleWrapTransform, $fixBladeTransform, $orderId)
    {		
    	$user =  DB::table('orders')
        ->select('users.*') ->where([
		    ['orders.id', '=', $orderId]
        ])
		->join(DB::raw('(SELECT * FROM `users`) users'), function($join)
		{
            $join->on('orders.id_user', '=', 'users.id');
        })
        ->first();
        $additions =  DB::table('additionOfBlade')
        ->select('additionOfBlade.*')->where([
            ['additionInOrder.id_order', '=', $orderId]
        ])
        ->join(DB::raw('(SELECT * FROM `additionInOrder`) additionInOrder'), function($join)
        {
            $join->on('additionOfBlade.id', '=', 'additionInOrder.id_addition');
        })->get();

        $knifeProperties = KnifePropertiesInOrder::where('id_order', $orderId)->first();
        $fultang = false;
        $klepkaPath = 'M640,0';
        if($knifeProperties->id_typeOfBolster == 5) {//фултанг
        	$fultang = true;
        	$klepkaPath = Handle::where('id', $knifeProperties->id_typeOfHandle)->value('pathKlepka');
        	$fixBladePath = Handle::where('id', $knifeProperties->id_typeOfHandle)->value('pathFixBladeFultang');
        } else{
        	$fixBladePath = Handle::where('id', $knifeProperties->id_typeOfHandle)->value('pathFixBlade');
        }
        $spusk = Spusk::where('id', SpuskInOrder::where('id_order', $orderId)->value('id_spusk'))->value('name');
        $order = Order::select('orders.*', DB::raw('(select DATE(`orders`.`created_at`)) as DateCreate'), DB::raw('(select TIME(`orders`.`created_at`)) as TimeCreate'))->where('id', $orderId)->get()->first();
        $order->DateCreate = date("d.m.Y", strtotime($order->DateCreate ));
        $order->TimeCreate = date("G:i", strtotime($order->TimeCreate));
    	if ($bladeLength > 165) {
		$file = '<svg id="svg" xmlns="http://www.w3.org/2000/svg" width="29.7cm" height="20.7cm" viewbox="0 0 1800 487">
				<g transform="scale(0.6236, 0.6236) translate(0 490)">';		
		         $c = 0;
			    for ($i = 0; $i < 1800; $i += 20) {
			     	$file .= '<line x1="' . $i . '" y1="480" x2="' . $i . '" y2="20" stroke="#ff0000" stroke-width="1"/>';
			        $c++;
			    }
		         $c = 0;
			    for ($i = 20; $i < 500; $i += 20) {
			     	$file .= '<line x1="0" y1="' . $i . '" x2="1800" y2="' . $i . '" stroke="#ff0000" stroke-width="1"/>';
			        $c++;
			    }
			    $file .= '<g id="bladePaths" transform="translate(600 108)">
		                <g id="blade_wrap_svg" transform="' . $bladeWrapTransform . '">
		                	<path id="blade_svg" transform="' . $bladeTransform . '" vector-effect="non-scaling-stroke" d="' . $pathBlade . '" fill="url(#patternSteel)" stroke-width="0.65" stroke="#000000"/>
		                </g>
                        <path id="fixBlade" transform="' . $fixBladeTransform . '" vector-effect="non-scaling-stroke"  fill="#ffffff" d="'.$fixBladePath.'"/> 
			            <g id="bolster_wrap_svg" transform="' . $bolsterWrapTransform . '">
			                <path id="bolster_svg" transform="' . $bolsterTransform . '" vector-effect="non-scaling-stroke" data-width="' . $bolsterWidth . '" d="'.$pathBolster . '"  fill="url(#patternBolster)" stroke-width="0.65" stroke="#000000"/>
		                </g>
		                <g id="handle_wrap_svg" transform="' . $handleWrapTransform . '">
		                	<path class="" id="handle_svg" transform="' . $handleTransform . '" vector-effect="non-scaling-stroke" d="' . $pathHandle . '" fill="url(#patternHandle)" stroke-width="0.65" stroke="#000000"/>
                                <path class="klepka" id="klepka" transform="' . $handleTransform . '" d="'.$klepkaPath.'" stroke ="#000000" stroke-width="0.75" vector-effect="non-scaling-stroke"  fill="url(#patternKlepka)"></path>
		                </g>
		            </g>
		            <g id="lineikaLines" transform="translate(0 162)">';
		$c = 0;
	    for ($i = 0; $i < 2400; $i += 40) {
	     	$file .= '<line x1="' . $i . '" y1="325" x2="' . $i . '" y2="305" stroke="#000000" stroke-width="2"/>';
	        for ($j = 0; $j < 10; $j++){
	            $file .= '<line x1="' . ($i + $j * 4) . '" y1="325" x2="' . ($i + $j * 4) . '" y2="315" stroke="#000000" stroke-width="1"/>';
	        }
	        $file .= '<text x="' . $i . '" y="300">' . $c . '</text>';
	        $c++;
	    }
	    $file .= '	</g>
	            	<g  transform="translate(0 -460)" font-weight="bold" font-size="24px">
	                	<text x="20" y="20" id="svg_blade_text" font-size="30px">Заказ №' . $orderId. '(' .$order->DateCreate.') Дней:' . $order->days_for_order . '</text>
	                	<text x="20" y="65">Длина клинка: ' . $bladeLength . ' мм</text>
	                	<text x="20" y="110">Спуски: ' . $spusk . '</text>
	                	<text x="20" y="155">Высота клинка: ' . $bladeHeight . ' мм</text>
	                	<text x="20" y="200">Длина ручки: ' . $handleLength . ' мм</text>
	                	<text x="20" y="245">Обух: ' . $buttWidth . ' мм</text>
	                	<text x="20" y="290" id="svg_blade_text">Клинок: ' . $blade . '</text>
	                	<text x="20" y="335" id="svg_steel_text">Сталь: ' . $steel . '</text>
	                	<text x="20" y="380" id="svg_bolster_text">Больстер: ' . $bolster . '</text>
	                	<text x="20" y="425" id="svg_handle_text">Ручка: ' . $handle . '</text>
	                	<text x="20" y="470" id="svg_handleMaterial_text">Материал ручки: ' . $handleMaterial . '</text>
	            	</g>
	            	<g  transform="translate(0 -460)" font-weight="bold" font-size="24px">
	            	<text x="600" y="240">Дополнительно:</text>';
	            	$i = 285;

	    foreach ($additions as $addition) {
	        $file .= '<text x="600" y="'.$i.'">' . $addition->name . '</text>';
	        $i+=45;
	    }
	            $file.='</g></g>
				<defs>
				    <pattern id="patternHandle" viewbox="0 0 1800 487" width="868" height="208"  patternUnits="userSpaceOnUse" x="-' . ($fultang ? 258 : 229) .'" y="-33">
                        <image id="handleImg" href="' . url('/img/patternsConstruct').'/'. $handleColor . '" width="' . ($fultang ? 314 : 284) .'" height="250" />
                    </pattern>
                    <pattern id="patternBolster" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1800 487" patternUnits="userSpaceOnUse"  x="-63" y="-58" width="680" height="300">
                        <image id="bolsterImg" width="930" height="400" href="' . url('/img/patternsConstruct').'/'. $bolsterColor . '" />
                    </pattern>
                    <pattern id="patternSteel" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1800 487" patternUnits="userSpaceOnUse" patternContentUnits="userSpaceOnUse" x="-495" y="-27" width="815" height="285">
                            <image id="steelImg" width="720" height="310" href="' . url('/img/patternsConstruct').'/'. $bladeColor . '" />
                    </pattern>
                    <pattern id="patternKlepka" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1800 487" patternUnits="userSpaceOnUse" patternContentUnits="userSpaceOnUse"  width="867.5" height="408" x="-285" y="-55">>
                                <image id="klepkaImg" width="830" height="250" href="'.asset('img/patternsConstruct').'/klepka.jpg'.'?'.VERSION.'"/>
                    </pattern>
                </defs>
	       	</svg>';
	    } else {
		$file = '<svg id="svg" xmlns="http://www.w3.org/2000/svg" width="29.7cm" height="20.7cm" viewbox="0 0 1200 335">
				<g transform="scale(0.945, 0.945) translate(0 490)">';
					
		         $c = 0;
			    for ($i = 0; $i < 2400; $i += 20) {
			     	$file .= '<line x1="' . $i . '" y1="325" x2="' . $i . '" y2="20" stroke="#ff0000" stroke-width="1"/>';
			        $c++;
			    }
		         $c = 0;
			    for ($i = 20; $i < 340; $i += 20) {
			     	$file .= '<line x1="0" y1="' . $i . '" x2="1400" y2="' . $i . '" stroke="#ff0000" stroke-width="1"/>';
			        $c++;
			    }
					$file .= '<g id="bladePaths" transform="translate(0 48)">
		                <g id="blade_wrap_svg" transform="' . $bladeWrapTransform . '">
		                	<path id="blade_svg" transform="' . $bladeTransform . '" vector-effect="non-scaling-stroke" d="' . $pathBlade . '" fill="url(#patternSteel)" stroke-width="0.65" stroke="#000000"/>
		                </g>
                        <path id="fixBlade" transform="' . $fixBladeTransform . '" vector-effect="non-scaling-stroke"  fill="#ffffff" d="'.$fixBladePath.'"/> 
			            <g id="bolster_wrap_svg" transform="' . $bolsterWrapTransform . '">
			                <path id="bolster_svg" transform="' . $bolsterTransform . '" vector-effect="non-scaling-stroke" data-width="' . $bolsterWidth . '" d="'.$pathBolster . '"  fill="url(#patternBolster)" stroke-width="0.65" stroke="#000000"/>
		                </g>
		                <g id="handle_wrap_svg" transform="' . $handleWrapTransform . '">
		                	<path class="" id="handle_svg" transform="' . $handleTransform . '" vector-effect="non-scaling-stroke" d="' . $pathHandle . '" fill="url(#patternHandle)" stroke-width="0.65" stroke="#000000"/>
                            <path class="klepka" id="klepka" transform="' . $handleTransform . '" d="'.$klepkaPath.'" stroke ="#000000" stroke-width="0.75" vector-effect="non-scaling-stroke"  fill="url(#patternKlepka)"></path>
		                </g>
		            </g>
		            <g id="lineikaLines" transform="translate(0 10)">';
				$c = 0;
	    for ($i = 0; $i < 2400; $i += 40) {
	     	$file .= '<line x1="' . $i . '" y1="325" x2="' . $i . '" y2="305" stroke="#000000" stroke-width="2"/>';
	        for ($j = 0; $j < 10; $j++){
	            $file .= '<line x1="' . ($i + $j * 4) . '" y1="325" x2="' . ($i + $j * 4) . '" y2="315" stroke="#000000" stroke-width="1"/>';
	        }
	        $file .= '<text x="' . $i . '" y="300">' . $c . '</text>';
	        $c++;
	    }
	    $file .= '	</g>
	            	<g  transform="translate(0 -460)" font-weight="bold" font-size="24px">
	                	<text x="20" y="20" id="svg_blade_text" font-size="30px">Заказ №' . $orderId. '(' .$order->DateCreate.') Дней:' . $order->days_for_order . '</text>
	                	<text x="20" y="65">Длина клинка: ' . $bladeLength . ' мм</text>
	                	<text x="20" y="110">Спуски: ' . $spusk . '</text>
	                	<text x="20" y="155">Высота клинка: ' . $bladeHeight . ' мм</text>
	                	<text x="20" y="200">Длина ручки: ' . $handleLength . ' мм</text>
	                	<text x="20" y="245">Обух: ' . $buttWidth . ' мм</text>
	                	<text x="20" y="290" id="svg_blade_text">Клинок: ' . $blade . '</text>
	                	<text x="20" y="335" id="svg_steel_text">Сталь: ' . $steel . '</text>
	                	<text x="20" y="380" id="svg_bolster_text">Больстер: ' . $bolster . '</text>
	                	<text x="20" y="425" id="svg_handle_text">Ручка: ' . $handle . '</text>
	                	<text x="20" y="470" id="svg_handleMaterial_text">Материал ручки: ' . $handleMaterial . '</text>
	            	</g>
	            	<g  transform="translate(0 -460)" font-weight="bold" font-size="24px">
	            	<text x="600" y="240">Дополнительно:</text>';
	            	$i = 285;

	    foreach ($additions as $addition) {
	        $file .= '<text x="600" y="'.$i.'">' . $addition->name . '</text>';
	        $i+=45;
	    }
	            $file.='</g></g>
				<defs>
				    <pattern id="patternHandle" viewbox="0 0 1800 487" width="868" height="208"  patternUnits="userSpaceOnUse" x="-' . ($fultang ? 258 : 229) .'" y="-33">
                        <image id="handleImg" href="' . url('/img/patternsConstruct').'/'. $handleColor . '" width="' . ($fultang ? 314 : 284) .'" height="250" />
                    </pattern>
                    <pattern id="patternBolster" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1800 487" patternUnits="userSpaceOnUse"  x="-63" y="-58" width="680" height="300">
                        <image id="bolsterImg" width="930" height="400" href="' . url('/img/patternsConstruct').'/'. $bolsterColor . '" />
                    </pattern>
                    <pattern id="patternSteel" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1800 487" patternUnits="userSpaceOnUse" patternContentUnits="userSpaceOnUse" x="-495" y="-27" width="815" height="285">
                            <image id="steelImg" width="720" height="310" href="' . url('/img/patternsConstruct').'/'. $bladeColor . '" />
                    </pattern>
                    <pattern id="patternKlepka" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1800 487" patternUnits="userSpaceOnUse" patternContentUnits="userSpaceOnUse"  width="867.5" height="408" x="-285" y="-55">>
                                <image id="klepkaImg" width="830" height="250" href="'.asset('img/patternsConstruct').'/klepka.jpg'.'?'.VERSION.'"/>
                    </pattern>
                </defs>
	       	</svg>';

	    }
		$fileopen = fopen("knife.svg", "a+");
		fwrite($fileopen, $file);
		fclose($fileopen);
		$file = ('knife.svg');
	    header('Content-Description: File Transfer');
	    header('Content-Type: application/octet-stream');
	    header('Content-Disposition: attachment; filename=' . basename($file));
	    header('Content-Transfer-Encoding: binary');
	    header('Expires: 0');
	    header('Cache-Control: must-revalidate');
	    header('Pragma: public');
	    header('Content-Length: ' . filesize($file));

	  if (readfile($file)) 
	  {
	    unlink($file);
	  }
	    exit;
    }
}

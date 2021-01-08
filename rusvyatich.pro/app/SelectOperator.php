<?php

namespace App;

use Illuminate\Support\Facades\DB;
use App\UserOwn;

class SelectOperator
{
	/*Возвращение менее занятого оператора*/
	public function getOperator()
	{	
		try {
			$operators = DB::table('users')->select('id')->where([
                ['status', '=', OPERATOR]
            ])->get();
			$operatorsCountOrder = DB::table('users')->select('users.id_operator', DB::raw('count(*) as count'))
			->where('users.id_operator', '<>', NULL)
	        ->groupBy('users.id_operator')
	        ->orderBy('count','ASC')
	        ->get();
	        $resOperator = array();
	        $k = 0;
	        foreach ($operators as $operator) {
	        	$resOperator[$k]['id'] =  $operator->id;
	        	$resOperator[$k]['count'] = 0;
	        	foreach ($operatorsCountOrder as $operatorCountOrder) {
	        		if ($operator->id === $operatorCountOrder->id_operator) {
	        			$resOperator[$k]['count'] = $operatorCountOrder->count;
	        		}
	        	}
	        	$k++;
	        }
	        $min = $resOperator[0]['count'];
	        $idMin = $resOperator[0]['id'];
	        foreach ($resOperator as $opearator) {
	        	if ($opearator['count'] < $min) {
	        		$min = $opearator['count'];
	        		$idMin = $opearator['id'];
	        	}
	        }
	        return $idMin;
		} catch (\Exception $e) {
			return $e->getMessage();
		}
       
	}
}

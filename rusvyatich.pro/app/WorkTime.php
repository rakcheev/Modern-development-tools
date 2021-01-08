<?php

namespace App;

class WorkTime
{
    public static function isWork()
    {
    	$date = getdate();
    	$date = $date['hours'];
    	if($date >= START_WORK_DAYSHIFT && $date < END_WORK_DAYSHIFT) {
    		return true;
    	} else {
    		return false;
    	}
    }
}

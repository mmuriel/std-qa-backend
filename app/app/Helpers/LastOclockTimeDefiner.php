<?php

namespace App\Helpers;

class LastOclockTimeDefiner{


public function defineLastOclockTime($timeBase){

		$baseMinutes = date("i",$timeBase);
		$baseSecs = date("s",$timeBase);
		$minutes = 0;

		if ($baseMinutes < 15){

			$minutes = 0;

		}
		else if ($baseMinutes < 30 && $baseMinutes >= 15){

			$minutes = 15;			

		}
		else if ($baseMinutes < 45 && $baseMinutes >= 30){

			$minutes = 30;

		}
		else{

			$minutes = 45;

		}

		$timeBase = ($timeBase - ( ($baseMinutes * 60) + $baseSecs) ) + ($minutes * 60);
		return $timeBase;

	}
}
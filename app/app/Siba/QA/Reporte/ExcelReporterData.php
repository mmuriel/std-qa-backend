<?php

namespace Siba\QA\Reporte;
use Siba\QA\Reporte\Interfaces\IReporterData;
use Illuminate\Support\Collection;

class ExcelReporterData implements IReporterData{

	public function normalizeData($data){

		$reportsGroupedByEvent = $data->groupBy('evento');

		$reportsGroupedByEvent->transform(function($item,$key){

			if ($item->count() > 1){
                $sortedEvtCollection = $item->sortByDesc('evento_fechahora');
                $tmpCol = Collection::make();
                $tmpCol->push($sortedEvtCollection->first());
                return $tmpCol;
            }
            else{
            	return $item;
            }

		});

		$data = $reportsGroupedByEvent->flatten(1);
		return $data;
	}

}
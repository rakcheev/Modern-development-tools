<?php

namespace App;

use App\Steel;
use App\Blade;
use App\Bolster;
use App\Handle;
use App\HandleMaterial;

class Popularity
{	
    public function shiftPopularityUpdate($type, $popularity, $prevPopularity)
	{
		switch ($type) {
			case STEEL_ID:
				if (Steel::where('popularity', $popularity)->exists()) {
					if($popularity < $prevPopularity) {
						Steel::where('popularity', '>=', $popularity)->increment('popularity');
					} elseif ($popularity > $prevPopularity) {
						Steel::where('popularity', '<=', $popularity)->decrement('popularity');
					}
				}
				break;
			
			case BLADE_ID:
				if (Blade::where('popularity', $popularity)->exists()) {
					if($popularity < $prevPopularity) {
						Blade::where('popularity', '>=', $popularity)->increment('popularity');
					} elseif ($popularity > $prevPopularity) {
						Blade::where('popularity', '<=', $popularity)->decrement('popularity');
					};
				}
				break;
			
			case BOLSTER_ID:
				if (Bolster::where('popularity', $popularity)->exists()) {
					if($popularity < $prevPopularity) {
						Bolster::where('popularity', '>=', $popularity)->increment('popularity');
					} elseif ($popularity > $prevPopularity) {
						Bolster::where('popularity', '<=', $popularity)->decrement('popularity');
					}
				}
				break;
			
			case HANDLE_ID:
				if (Handle::where('popularity', $popularity)->exists()) {
					if($popularity < $prevPopularity) {
						Handle::where('popularity', '>=', $popularity)->increment('popularity');
					} elseif ($popularity > $prevPopularity) {
						Handle::where('popularity', '<=', $popularity)->decrement('popularity');
					}
				}
				break;
			
			case HANDLE_MATERIAL_ID:
				if (HandleMaterial::where('popularity', $popularity)->exists()) {
					if($popularity < $prevPopularity) {
						HandleMaterial::where('popularity', '>=', $popularity)->increment('popularity');
					} elseif ($popularity > $prevPopularity) {
						HandleMaterial::where('popularity', '<=', $popularity)->decrement('popularity');
					}
				}
				break;
			
			default:
				# code...
				break;
		}
	}

    public function shiftPopularityInsert($type, $popularity)
	{
		switch ($type) {
			case STEEL_ID:
				if (Steel::where('popularity', $popularity)->exists()) {
					Steel::where('popularity', '>=', $popularity)->increment('popularity');
				}
				break;
			
			case BLADE_ID:
				if (Blade::where('popularity', $popularity)->exists()) {
					Blade::where('popularity', '>=', $popularity)->increment('popularity');
				}
				break;
			
			case BOLSTER_ID:
				if (Bolster::where('popularity', $popularity)->exists()) {
					Bolster::where('popularity', '>=', $popularity)->increment('popularity');
				}
				break;
			
			case HANDLE_ID:
				if (Handle::where('popularity', $popularity)->exists()) {
					Handle::where('popularity', '>=', $popularity)->increment('popularity');
				}
				break;
			
			case HANDLE_MATERIAL_ID:
				if (HandleMaterial::where('popularity', $popularity)->exists()) {
					HandleMaterial::where('popularity', '>=', $popularity)->increment('popularity');
				}
				break;
			
			default:
				# code...
				break;
		}
	}

	public function arrangePopularity($type) {
		switch ($type) {
			case STEEL_ID:
				$steels = Steel::orderBy('popularity', 'asc')->get();
				$i = 1;
				foreach ($steels as $steel) {
					$steel->popularity = $i;
					$steel->save();
					$i++;
				}
				break;
			
			case BLADE_ID:
				$blades = Blade::orderBy('popularity', 'asc')->get();
				$i = 1;
				foreach ($blades as $blade) {
					$blade->popularity = $i;
					$blade->save();
					$i++;
				}
				break;
			
			case BOLSTER_ID:
				$bolsters = Bolster::orderBy('popularity', 'asc')->get();
				$i = 1;
				foreach ($bolsters as $bolster) {
					$bolster->popularity = $i;
					$bolster->save();
					$i++;
				}
				break;
			
			case HANDLE_ID:
					$handles = Handle::orderBy('popularity', 'asc')->get();
					$i = 1;
					foreach ($handles as $handle) {
						$handle->popularity = $i;
						$handle->save();
						$i++;
					}
				break;
			
			case HANDLE_MATERIAL_ID:
				$handleMaterials = HandleMaterial::orderBy('popularity', 'asc')->get();
				$i = 1;
				foreach ($handleMaterials as $handleMaterial) {
					$handleMaterial->popularity = $i;
					$handleMaterial->save();
					$i++;
				}
				break;
			
			default:
				break;
		}
	}
}

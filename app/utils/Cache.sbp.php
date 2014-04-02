<?

Cache:\Illuminate\Support\Facades\Cache

	s+ push $key, $value, $minutes = null, $noDoublon = false
		$array = static::get($key)
		if ! is_array($array)
			$array = array()
		if ! $noDoublon || ! in_array($value, $array)
			$array[] = $value
		< is_null($minutes) ?
			static::forever($key, $array) :
			static::set($key, $array, $minutes)
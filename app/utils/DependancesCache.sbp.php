<?

DependancesCache

	s+ key $file
		< 'DependancesCache-'. realpath($file)

	s+ flush $file
		< Cache::forever(static::key($file), array())

	s+ add $file, $dependance
		< Cache::push(static::key($file), realpath($dependance))

	s+ get $file
		< (array) Cache::get(static::key($file))

	s+ lastTime $file, $function = 'filemtime'
		$time = $function($file)
		foreach static::get($file) as $dependance
			$test = $function($dependance)
			if $test > $time
				$time = $test
		< $time
<?

/*
 * Log de clic sur un lien sortant
 */
LogSearch:Model

	* $collection = 'log_search'
	* $fillable = array('search_query', 'ip', 'results', 'created_at')
	+ $timestamps = false

	s+ log $searchQuery = '', $results = 0
		<static::create(array(
			'search_query' => $searchQuery,
			'ip' => ip2bin(),
			'results' => $results,
			'created_at' => new DateTime
		))

	s+ mine
		<static::where('ip', ip2bin())
			->select('search_query', 'created_at', 'results')
			->groupBy('id')
			->orderBy('created_at', 'desc')

	s+ startWith $searchQuery = ''
		<static::select('search_query', DB::raw('SUM(results) AS sum_results'), DB::raw('COUNT(DISTINCT ip) AS count'))
			->whereRaw('LOWER(search_query) LIKE ?', array(addcslashes(strtolower($searchQuery), '_%') . '%'))
			->whereRaw('LENGTH(search_query) > 1')
			->where('results', '>', 0)
			->groupBy('search_query')
			->orderBy('count', 'desc')
			->orderBy('sum_results', 'desc')
			->take(8)
			->remember()
			->lists('search_query')
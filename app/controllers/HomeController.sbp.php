<?

HomeController:BaseController

	+ searchBar
		<>view('home');
	
	+ searchResultForm $page = 1, $q = null, $resultsPerPage = null
		<>searchResult($page, $q, $resultsPerPage, true);

	+ searchResult $page = 1, $q = null, $resultsPerPage = null, $form = false
		$q = is_null($q) ? Request::get('q', $page) : urldecode($q);
		$data = CrawledContent::getSearchResult($q)
				->paginatedData($page, $resultsPerPage, array(
				'q' => $q,
				'pageUrl' => '/%d/'.urlencode($q).'{keepResultsPerPage}',
				'resultsPerPageUrl' => '/'.$page.'/'.urlencode($q).'/%d'
			));
		if $form
			LogSearch::log($q, $data['nbResults']);
		<>view('result', $data);

	+ goOut $search_query, $id
		$result = CrawledContent::find($id);
		if !$result
			App::abort(404);

		LogOutgoingLink::create(array(
			'search_query' => $search_query,
			'crawled_content_id' => $id
		));
		$count = Cache::get('crawled_content_id:'.$id.'_log_outgoing_link_count');
		if $count
			$count++;
		else
			$count = LogOutgoingLink::where('crawled_content_id', $id)->count();
		Cache::put('crawled_content_id:'.$id.'_log_outgoing_link_count', $count, CrawledContent::REMEMBER);

		<Redirect::to($result->url);

	+ addUrl
		$url = Input::get('url');
		$state = scanUrl($url);
		<>view('home', array(
			'url' => $url,
			'state' => $state
		));

	+ mostPopular $page, $resultsPerPage = null
		<>view('result',
			CrawledContent::popular()
				->select(
					'crawled_contents.id',
					'url', 'title', 'content', 'language',
					DB::raw('COUNT(log_outgoing_links.id) AS count')
				)
				->orderBy('count', 'desc')
				->paginatedData($page, $resultsPerPage, array(
					'q' => '',
					'pageUrl' => '/most-popular/%d{keepResultsPerPage}',
					'resultsPerPageUrl' => '/most-popular/'.$page.'/%d'
				))
			);

	+ history $page, $resultsPerPage = null
		$data = LogSearch::mine()
			->paginatedData($page, $resultsPerPage, array(
				'q' => '',
				'pageUrl' => '/history/%d{keepResultsPerPage}',
				'resultsPerPageUrl' => '/history/'.$page.'/%d'
			));
		$data['resultsGroups'] = $data['results']->groupBy(f° $element
			<$element->created_at->uRecentDate;
		);
		<>view('history', $data);
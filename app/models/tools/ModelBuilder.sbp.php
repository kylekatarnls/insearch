<?php

/**
 * ModelBuilder apporte quelques améliorations au Builder de Eloquent
 */
ModelBuilder:Illuminate\Database\Eloquent\Builder

	REMEMBER = 10;
	// Entrer une valeur en minutes pour la durée de mise en cache des requêtes SQL
	// ou false pour ne pas mettre les résultats de requêtes SQL en cache

	/**
	 * Retourne des variables exploitables pour afficher des données paginées dans la view 
	 *
	 * @return ModelBuilder $dataForView
	 */
	+ paginatedData &$page, $resultsPerPage, array $mergedData = array()
		$nbResults = clone $this
		$nbResults = $nbResults->count()
		ResultsPerPage::paginate($nbResults, $page, $choice, $resultsPerPage, $nbPages, $mergedData)
		<array_merge(
			array(
				'nbPages' => (int) $nbPages,
				'currentPage' => (int) $page,
				'results' => $this->forPage($page, $resultsPerPage)->get(),
				'nbResults' => (int) $nbResults,
				'resultsPerPage' => (int) $resultsPerPage,
				'choiceResultsPerPage' => $choice
			),
			$mergedData
		)

	/**
	 * Amélioration de ->count() : élimine les éventuels éléments de requêtes entrant en conflit avec
	 * le COUNT() de SQL
	 *
	 * @return int $nombreDeResultats
	 */
	+ count $column = null
		if is_null($column)
			>query->select(array())
			>query->orders = null
			>query->groups = null
			$column = DB::raw('DISTINCT ' . $this->query->from . '.id')
		<parent::count($column)

	+ remember $remember = null
		if is_null($remember)
			$remember = :REMEMBER
		if $remember
			>remember($remember)
		<$this

?>
<?php

class ModelsTest extends TestCase {

	/**
	 * Vérifie que la classe Model fonctionne
	 *
	 * @return void
	 */
	public function testModel()
	{

		$crawledContent = new CrawledContent;
		$this->assertTrue(method_exists($crawledContent, 'newQuery'), "CrawledContent->newQuery() devrait exister");
		$this->assertTrue($crawledContent->newQuery() instanceof ModelBuilder, "CrawledContent->newQuery() devrait retourner une instance de ModelBuilder");
		$this->assertTrue($crawledContent->crossDriver(array('default' => 'truc')) === 'truc', "CrawledContent->crossDriver(array('default' => 'truc')) devrait retourner 'truc'");
		$this->assertTrue(method_exists($crawledContent, 'findAndCount'), "CrawledContent->findAndCount() devrait exister");
		$this->assertTrue(method_exists($crawledContent, 'substr'), "CrawledContent->substr() devrait exister");
		$this->assertTrue(method_exists($crawledContent, 'substring'), "CrawledContent->substring() devrait exister");
		$this->assertTrue(method_exists($crawledContent, 'caseWhen'), "CrawledContent->caseWhen() devrait exister");
		$this->assertTrue($crawledContent->search('truc') instanceof ModelBuilder, "CrawledContent->search() devrait retourner une instance de ModelBuilder");
		$this->assertTrue(Model::REMEMBER === false || is_int(Model::REMEMBER), "Model::REMEMBER devrait être un nombre entier (int) ou false");
		$this->assertTrue(is_int(Model::KEY_WORD_SCORE), "Model::KEY_WORD_SCORE devrait être un nombre entier (int)");
		$this->assertTrue(is_int(Model::COMPLETE_QUERY_SCORE), "Model::COMPLETE_QUERY_SCORE devrait être un nombre entier (int)");
		$this->assertTrue(is_int(Model::ONE_WORD_SCORE), "Model::ONE_WORD_SCORE devrait être un nombre entier (int)");

	}

	/**
	 * Vérifie que la classe CrawledContent fonctionne
	 *
	 * @return void
	 */
	public function testCrawledContent()
	{

		$this->assertTrue(is_int(CrawledContent::SAME_LANGUAGE), "CrawledContent::SAME_LANGUAGE devrait être un nombre entier (int)");
		$this->assertTrue(is_int(CrawledContent::SAME_PRIMARY_LANGUAGE), "CrawledContent::SAME_PRIMARY_LANGUAGE devrait être un nombre entier (int)");
		$crawledContent = new CrawledContent;
		$this->assertTrue($crawledContent->getSearchResult('truc') instanceof ModelBuilder, "CrawledContent->getSearchResult() devrait retourner une instance de ModelBuilder");
		$this->assertTrue($crawledContent->popular() instanceof ModelBuilder, "CrawledContent->popular() devrait retourner une instance de ModelBuilder");
		$this->assertTrue(method_exists($crawledContent, 'keyWords'), "CrawledContent->keyWords() devrait exister");
		$this->assertTrue(method_exists($crawledContent, 'scan'), "CrawledContent->scan() devrait exister");
		$this->assertTrue(is_string($crawledContent->outgoingLink), "CrawledContent->outgoingLink devrait retourner une chaîne (string)");
		$this->assertTrue(is_string($crawledContent->urlAndLanguage), "CrawledContent->urlAndLanguage devrait retourner une chaîne (string)");
		$this->assertTrue(is_numeric($crawledContent->count), "CrawledContent->count devrait retourner une valeur numérique");
		$this->assertTrue(is_string($crawledContent->content), "CrawledContent->content devrait retourner une chaîne (string)");
		$this->assertTrue(is_string($crawledContent->title), "CrawledContent->title devrait retourner une chaîne (string)");

	}

	/**
	 * Vérifie que la classe KeyWord fonctionne
	 *
	 * @return void
	 */
	public function testKeyWord()
	{

		$this->assertTrue(method_exists('KeyWord', 'crawledContents'), "KeyWord->crawledContents() devrait exister");

	}

	/**
	 * Vérifie que la classe LogOutgoingLink fonctionne
	 *
	 * @return void
	 */
	public function testLogOutgoingLink()
	{

		$logOutgoingLink = LogOutgoingLink::first();
		$count = $logOutgoingLink->count();
		$this->assertTrue(is_numeric($count), "LogOutgoingLink->count() devrait retourner une valeur numérique");
		$this->assertTrue($count === 0 || $logOutgoingLink->created_at instanceof TranslatableDateTime, "LogOutgoingLink->created_at devrait exister");

	}

	/**
	 * Vérifie que la classe LogSearch fonctionne
	 *
	 * @return void
	 */
	public function testLogSearch()
	{

		$this->assertTrue(method_exists('LogSearch', 'log'), "LogSearch->log() devrait exister");
		$this->assertTrue(LogSearch::mine() instanceof ModelBuilder, "LogSearch::mine() devrait retourner une instance de ModelBuilder");
		$this->assertTrue(is_array(LogSearch::startWith('a')), "LogSearch::startWith('a') devrait retourner un array");
		$page = -2;
		$this->assertTrue(is_array(LogSearch::mine()->paginatedData($page, 40)), "LogSearch->paginatedData() devrait retourner un array");
		$this->assertTrue($page === 1, "\$page devrait valloir 1");

	}
}
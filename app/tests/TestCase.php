<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase {

	/**
	 * Creates the application.
	 *
	 * @return \Symfony\Component\HttpKernel\HttpKernelInterface
	 */
	public function createApplication()
	{
		$unitTesting = true;

		$testEnvironment = 'testing';

		return require __DIR__.'/../../bootstrap/start.php';
	}

	protected function getUrl($url)
	{
		try
		{
			return file_get_contents('http://insearch.selfbuild.fr' . $url);
		}
		catch(ErrorException $e)
		{
			return false;
		}
	}

}
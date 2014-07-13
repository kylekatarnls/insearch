<?

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use sbp\sbp;
use sbp\laravel\ClassLoader;

AssetsCompileCommand:Command

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	* $name = 'asset:compile'

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	* $description = 'Compile/Recompile all the assets.'

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	+ __construct
		parent::__construct()

	/**
	 * Get list of assets from a directory recursively.
	 *
	 * @return array $files
	 */
	- files $assetsDirectory, $directory, $extension = null
		$files = array()
		foreach scandir($assetsDirectory . '/' . $directory) as $file
			if substr($file, 0, 1) not '.'
				$path = $directory . '/' . $file
				if is_file($assetsDirectory . '/' . $path)
					if is_null($extension) || substr($path, -1-strlen($extension)) is '.' . $extension
						$files[] = $path
				else
					array_merge(**$files, >files($assetsDirectory, $path, $extension))
		< $files

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	+ fire

		sbp_include_once(app_path() . '/utils/functions')
		checkAssets(true)
		$assetsDirectory = app_path().'/assets'

		foreach array('image', 'script', 'style') as $asset
			$count = 0
			$plural = $asset . 's'
			foreach >files($assetsDirectory, $plural) as $file
				echo "     $file\n"
				$asset($file)
				$count++
			echo $count . " fichiers $plural copiés\n\n"
		$fail = 0
		$success = 0
		$file = app_path() . '/routes'
		$ok = sbp::fileExists($file)
		${$ok ? 'success' : 'fail'}++
		echo "    " . $file . " : " . ($ok ? "OK" : "Erreur") . "\n"
		ClassLoader::register(true, 'sbp2phpFilePath')
		foreach >files(app_path(), '', 'sbp.php') as $file
			if substr($file, -14) not 'routes.sbp.php'
				$path = realpath(app_path() . $file)
				$ok = sbp::fileExists(substr($path, 0, -8), $phpFile)
				${$ok ? 'success' : 'fail'}++
				realpath(**$phpFile)
				echo "    " . $file . " > " . $phpFile . " : "
				if file_exists($phpFile . '.log')
					echo "Erreur (voir " . $phpFile . ".log)\n"
				else
					echo ($ok ? "OK" : "Erreur") . "\n"
		echo "\n" . $success . " fichiers SBP copiés\n" . $fail . " fichiers SBP échoués\n\n"

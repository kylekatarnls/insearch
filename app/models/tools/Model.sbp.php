<?php

/**
 * Modèle abstrait doté d'outils de recherche
 */
a Model:Eloquent

	KEY_WORD_SCORE = 10
	COMPLETE_QUERY_SCORE = 5
	ONE_WORD_SCORE = 1

	s* $lastQuerySearch = ''

	// les fonctionnalités avancées de TranslatableDateTime sont accessible dans tous les modèles
	* asDateTime $value
		<new TranslatableDateTime(parent::asDateTime($value))

	// Surcharge de newQuery
	+ newQuery $excludeDeleted = true
		// Code original de Illuminate\Database\Eloquent\Builder
		// Seul Builder a été remplacé par ModelBuilder
		$builder = new ModelBuilder(>newBaseQueryBuilder())

		$builder->setModel($this)->with(>with)

		if $excludeDeleted && >softDelete
			$builder->whereNull(>getQualifiedDeletedAtColumn())

		<$builder

	s+ crossDriver array $methods
		$driver = DB::getDefaultConnection()
		if(!isset($methods[$driver]))
			$driver = 'default'
			if(!isset($methods[$driver]))
				<head($methods)
		<$methods[$driver]

	s+ findAndCount $whereToFind, $wordsToFind
		if !is_array($wordsToFind)
			$wordsToFind = array($wordsToFind)
		$replace = self::quote($whereToFind, false)
		$static = new static
		<DB::raw(
			'(' . implode(' + ', array_map(f° $word use $replace, $static
				< '(
					(
						LENGTH(' . $replace . ') -
						LENGTH(REPLACE(LOWER(' . $replace . '), LOWER(' . $static::quote($word, false) . '), \'\'))
					)
					/ ' . strlen($word) .'
				)';
			, $wordsToFind)) . ')'
		)

	s+ substr $string, $offset, $length = null
		<DB::raw(
			static::crossDriver(array(
				'sqlite' => 'SUBSTR',
				'default' => 'SUBSTRING'
			)) .
			'(' . self::quote($string) . ', ' . self::quote($offset) . (is_null($length) ? '' : ', ' . self::quote($length)) . ')'
		)

	s+ substring $string, $offset, $length = null
		<static::substr($string, $offset, $length)

	s+ caseWhen $case, $when = null, $else = null
		if is_array($case)
			$else = $when
			$when = $case
			$case = null
		$return = '(CASE ' . self::quote($case) . ' '
		if !empty($when) && is_array($when)
			foreach $when as $if => $then
				$return .= 'WHEN ' . self::quote($if) . ' THEN ' . self::quote($then) . ' '
		if !is_null($else)
			$return .= 'ELSE ' . self::quote($else) . ' '
		$return .= 'END)'
		<DB::raw($return)

	s* quote $value, $numberAllowed = true
		if ($numberAllowed && (is_int($value) || is_float($value))) || $value instanceof Expression
			<strval($value)
		<DB::getPdo()->quote($value)

	s* words $value
		if !is_array($value)
			if count($tab = explode('"', $value)) > 2
				$value = array()
				foreach $tab as $i => $val
					$val = trim($val)
					if !empty($val)
						if $i & 1
							$value[] = $val
						else
							$value = array_merge($value, preg_split('#\s+#', $val))
			else
				$value = preg_split('#\s+#', $value)
		<$value

	+ getFillable
		<>fillable

    /**
     * Prépare une requête de recherche
     * 
     * $values peut être passée par référence pour récupérer
     * 
     * @return QueryBuilder $result
     */
	s+ search $value = '', &$values = null
		$values = self::words($value)
		if $value === ''
			<self::whereRaw('1 = 0')
		static::$lastQuerySearch = urlencode($value)
		$static = new static
		<static::where(f° $result use $values, $static
			<call_user_func(
				$static::crossDriver(
					array(
						'pgsql' => f° use $result, $values
							foreach $values as $value
								$result->orWhereRaw(
									"searchtext @@ to_tsquery(?)",
									array($value)
								)
							<$result
						,
						'default' => f° use $result, $values, $static
							$fillable = $static->getFillable()
							foreach $values as $value
								foreach $fillable as $column
									$result->orWhereRaw(
										'LOWER(' . $column . ') LIKE ?',
										array('%' . addcslashes(strtolower($value), '_%') . '%')
									)
							<$result
					)
				)
			)
		})->remember()

?>
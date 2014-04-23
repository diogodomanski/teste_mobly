<?php

return array(
	'doctrine' => array(
		'connection' => array(
			'orm_default' => array(
				'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
				'params' => array(
					'host' => '127.0.0.1',
					'port' => '3306',
					'user' => 'root',
					'password' => '',
					'dbname' => 'teste_mobly',
					'driverOptions' => array(
						PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"
					),
				)
			),
		),
		
		'configuration' => array(
			'orm_default' => array(
				//'sql_logger' => new MOBBase\Doctrine\DBAL\Logging\FileSQLLogger(array('log_filename' => '/var/tmp/doctrine_sql.log')),
				'datetime_functions' => array(
					'DATE' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\Date',
					//'DATE_ADD' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\DateAdd',
					//'DATE_DIFF' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\DateDiff',
					'DATE_FORMAT' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\DateFormat',
					'DAY' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\Day',
					'HOUR' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\Hour',
					'MONTH' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\Month',
					'STR_TO_DATE' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\StrToDate',
					'TIMESTAMPDIFF' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\TimestampDiff',
					'WEEK' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\Week',
					'YEAR' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\Year',
				),
				'numeric_functions' => array(
					'ACOS' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\Acos',
					'ASIN' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\Asin',
					'ATAN' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\Atan',
					'ATAN2' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\Atan2',
					'CHAR_LENGTH' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\CharLength',
					'COS' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\Cos',
					'COT' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\Cot',
					'COUNT_IF' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\CountIf',
					'DEGREES' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\Degress',
					'IF' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\IfElse',
					'IFNULL' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\IfNull',
					'NULLIF' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\NullIf',
					'PI' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\Pi',
					'RADIANS' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\Radians',
					'RAND' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\Rand',
					'ROUND' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\Round',
					'SIN' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\Sin',
					'TAN' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\Tan',
				),
				'string_functions' => array(
					'BINARY' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\Binary',
					'CONCAT_WS' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\ConcatWs',
					'CRC32' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\Crc32',
					'FIELD' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\Field',
					'FIND_IN_SET' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\FindInSet',
					'GROUP_CONCAT' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\GroupConcat',
					'MATCH_AGAINST' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\MatchAgainst',
					'MD5' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\Md5',
					'REGEXP' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\Regexp',
					'SHA1' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\Sha1',
					'SHA2' => '\MOBBase\Doctrine\ORM\Query\AST\Functions\Sha2',
				)
			)
        )
	)
);
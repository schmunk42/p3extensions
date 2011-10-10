<?php

/**
 * Based upon http://www.yiiframework.com/doc/guide/1.1/en/database.migration#c2550 from Leric
 */
class P3DumpSchemaCommand extends CConsoleCommand {

	public function run($args) {
		$schema = $args[0];
		$prefix = $args[1];
		$tables = Yii::app()->db->schema->getTables($schema);
		$code = '';
		$code .= "if (Yii::app()->db->schema instanceof CMysqlSchema)\n";
		$code .= "	\$options = 'ENGINE=InnoDB DEFAULT CHARSET=utf8';\n";
		$code .= "else\n";
		$code .= "	\$options = '';\n";
		
		foreach ($tables as $table) {
			if (substr($table->name, 0, strlen($prefix)) != $prefix)
				continue;

			$code .= "\n\n\n// Schema for table '" . $table->name . "'\n\n";
			$code .= $this->generateSchema($table, $schema);

			$code .= "\n\n\n// Foreign Keys for table '" . $table->name . "'\n\n";
			$code .= "if ((Yii::app()->db->schema instanceof CSqliteSchema) == false):\n";
			$code .= $this->generateForeignKeys($table, $schema);
			$code .= "\nendif;\n";

			$code .= "\n\n\n// Data for table '" . $table->name . "'\n\n";
			$code .= $this->generateInserts($table, $schema);
		}
		echo $code;
	}

	private function generateSchema($table, $schema) {
		$options = "ENGINE=InnoDB DEFAULT CHARSET=utf8";
		$code = '';
		$code .= '$this->createTable("' . $table->name . '", ';
		$code .= "\n";
		$code .= '  array(' . "\n";
		foreach ($table->columns as $col) {
			$code .= '    "' . $col->name . '"=>"' . $this->resolveColumnType($col) . '",' . "\n";
		}
		
		// special case for non-auto-increment PKs
		$code .= $this->generatePrimaryKeys($table->columns);
		$code .= "\n";
		$code .= '  ), ';
		$code .= "\n";
		$code .= '  $options);';
		return $code;
	}


	private function generatePrimaryKeys($columns) {
		foreach ($columns as $col) {			
			if ($col->isPrimaryKey && !$col->autoIncrement) {
				return '    "PRIMARY KEY ('.$col->name.')"';
			}
		}
	}

	private function generateForeignKeys($table, $schema) {
		$code = "";
		foreach ($table->foreignKeys as $name => $foreignKey) {
			#echo "FK" . $name . var_dump($foreignKey);
			$code .= "\n\$this->addForeignKey('fk_{$foreignKey[0]}_{$name}', '{$table->name}', '{$name}', '{$foreignKey[0]}', '{$foreignKey[1]}', null, null); // update 'null' for ON DELTE and ON UPDATE\n";
		}
		return $code;
	}

	private function generateInserts($table, $schema) {
		$code = '';
		$data = Yii::app()->db->createCommand()
			->from($table->name)
			->query();

		foreach ($data AS $row) {
			$code .= '$this->insert("' . $table->name . '", array(' . "\n";
			foreach ($row AS $column => $value) {
				$code .= '    "' . $column . '"=>' . (($value === null) ? 'null' : '"' . $value . '"') . ',' . "\n";
			}
			$code .= ') );' . "\n\n";
		}
		return $code;
	}

	private function resolveColumnType($col) {
		if ($col->isPrimaryKey && $col->autoIncrement) {
			return "pk";
		}

		$result = $col->dbType;

		if (!$col->allowNull) {
			$result .= ' NOT NULL';
		}
		if ($col->defaultValue != null) {
			$result .= " DEFAULT '{$col->defaultValue}'";
		}
		return $result;
	}

}

?>
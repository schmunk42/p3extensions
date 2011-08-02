<?php 
/**
 * Based upon http://www.yiiframework.com/doc/guide/1.1/en/database.migration#c2550 from Leric
 */

class DumpSchemaCommand extends CConsoleCommand
{
 
    public function run($args) {
        $schema = $args[0];
        $tables = Yii::app()->db->schema->getTables($schema);
        $result = '';
        foreach ($tables as $def) {
            $result .= '$this->createTable("' . $def->name . '", array(' . "\n";
            foreach ($def->columns as $col) {
                $result .= '    "' . $col->name . '"=>"' . $this->getColType($col) . '",' . "\n";
            }
            $result .= '), "");' . "\n\n";
            
            $data = Yii::app()->db->createCommand()
                ->from($def->name)
                ->query();

			foreach ($data AS $row) {
                $result .= '$this->insert("' . $def->name . '", array(' . "\n";
                foreach($row AS $column => $value) {
                    $result .= '    "' . $column . '"=>' .  (($value===null)?'null':'"'.$value.'"') . ',' . "\n";
                }
                $result .= ') );' . "\n\n";
            }
        }
        echo $result;
    }
 
    public function getColType($col) {
        if ($col->isPrimaryKey && $col->autoIncrement) {
            return "pk";
        }

		$result = $col->dbType;

        if ($col->isPrimaryKey) {
            #$result .= ' PRIMARY';
        }
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
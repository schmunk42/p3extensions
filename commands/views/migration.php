<?php echo "
<?php

class {$migrationClassName} extends CDbMigration {

	public function safeUp() {
		{$functionUp}
	}

	public function safeDown() {
		echo 'Migration down not supported.';
	}

}

?>
";
?>
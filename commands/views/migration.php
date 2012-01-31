<?php echo "
<?php

class {$migrationClassName} extends CDbMigration {

	public function up() {
		{$functionUp}
	}

	public function down() {
		echo 'Migration down not supported.';
	}

	/*
	  // Use safeUp/safeDown to do migration with transaction
	  public function safeUp()
	  {
	  }

	  public function safeDown()
	  {
	  }
	 */
}

?>
";
?>
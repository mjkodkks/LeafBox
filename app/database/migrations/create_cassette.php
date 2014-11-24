<?php 
	class CreateCassette extends Migration {
		public function up()
		{
			Schema::create('cassette',function($table){
				$table->increment('id');
				$table->integer('book_id');
				$table->integer('numpart');
				$table->string('reserved',50);
				$table->date('produced_date');
				$table->string('notes',50);

			});

		}

		public function down()
		{
			Schema::drop('cassette');

		}

	}

 ?>
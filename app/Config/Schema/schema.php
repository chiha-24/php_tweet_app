<?php 
class AppSchema extends CakeSchema {

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	var $tweets = array(

		'id' => array(
			'type' => 'integer',
			'null' => false,
			'extra' => 'auto_increment',
			'key' => 'primary',
			'default' => null
			),
		'created' => array(
			'type' => 'datetime',
			'null' => false,
			'default' => null
			),

		'tw_id' => array(
			'type' => 'string',
			'null' => false,
			'default' => null
		),
			
		'image' => array(
			'type' => 'string',
			'null' => false,
			'default' => null
		),

		'indexes' => array(
		  'PRIMARY' => array(
				'column' => array(
					'id'
				),
				'unique' => 1
			),
			'UNIQUE' => array(
				'column' => array(
					'image'
				),
				'unique' => 1
			)
		)
	);

}

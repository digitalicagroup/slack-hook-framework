<?php

namespace SlackHookFramework;

/**
 * Abstract wrapper class for an array.
 *
 * @author Luis Augusto Peña Pereira <luis at digitalicagroup dot com>
 *        
 */
abstract class AbstractArray {
	/**
	 * Internal array to be wrapped.
	 *
	 * @var array an associative array of strings that can be converted to a json.
	 */
	protected $a;
	
	/**
	 * Constructor.
	 * Initializes the internal array
	 */
	public function __construct() {
		$this->a = array ();
	}
	
	/**
	 * Returns the internal array.
	 *
	 * @return array
	 */
	public function toArray() {
		return $this->a;
	}
	
	/**
	 * Returns the json representation of the internal array.
	 *
	 * @return string
	 */
	public function toJson() {
		return json_encode ( $this->a );
	}
	public function toJsonPretty() {
		return json_encode ( $this->a, JSON_PRETTY_PRINT );
	}
	
	/**
	 * Stores a child array with the $key key.
	 *
	 * @param string $key        	
	 * @param array $objs_array
	 *        	array of \SlackHookFramework\AbstractArray (subclasses) instances.
	 */
	public function setArray($key, $objs_array) {
		$this->a [$key] = array ();
		foreach ( $objs_array as $obj ) {
			$this->a [$key] [] = $obj->toArray ();
		}
	}
	
	/**
	 * Getter method for a specific value referenced by the given key.
	 *
	 * @param string $key        	
	 */
	public function getValue($key) {
		if (isset ( $this->a [$key] )) {
			return $this->a [$key];
		} else {
			return NULL;
		}
	}
}
?>
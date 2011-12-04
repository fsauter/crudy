<?php

class {className} {
	<!-- startBlock(fields) -->
	private ${name};<!-- endBlock(fields) -->
	
	public function __construct() {}
	
	<!-- startBlock(getterFields) -->
	public function get{uname}() {
		return $this->{name};
	}
	<!-- endBlock(getterFields) -->
	<!-- startBlock(setterFields) -->
	public function set{uname}(${name}) {
		$this->{name} = {name}; 
	}
	<!-- endBlock(setterFields) -->
}
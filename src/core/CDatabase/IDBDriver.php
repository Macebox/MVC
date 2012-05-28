<?php

interface IDBDriver
{
	/*
	*	<--- AND EXAMPLE --->
	*	$equals =
	*	array(
	*		'column'	=> 'value',
	*		'column2'	=> 'value2'
	*		);
	*	-----> WHERE column='value' AND column2='value2'
	*
	*	<--- OR EXAMPLE --->
	*	$equals =
	*	array(
	*		'column'	=> 'value',
	*		array(
	*			'column2'	=> 'value2',
	*			'column3'	=> 'value3',
	*			array(
	*				'column2' => 'value3'
	*				)
	*			)
	*		);
	*	-----> WHERE column='value' AND (column2='value2' OR column3='value3' OR (column2='value3'))
	*
	*
	*
	*
	**/
	
	
	public function Get($table, $columns, $equals, $order, $asc, $distinct);
	
	public function Insert($table, $columns);
	
	public function Delete($table, $equals);
	
	public function Update($table, $columns, $equals);
	
	public function GetQueries();
	
	public function RunQuery($q, $secure=false);
	
	public function getLastId();
}
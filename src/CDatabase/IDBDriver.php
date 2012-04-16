<?php

interface IDBDriver
{
	public function Get($table, $columns, $equals);
	
	public function Insert($table, $columns);
	
	public function Delete($table, $equals);
	
	public function Update($table, $columns, $equals);
	
	public function GetQueries();
}
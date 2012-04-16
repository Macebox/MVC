<?php

interface IHasSQL
{
	public function Add($entry);
	
	public function DeleteAll();
	
	public function ReadAll();
}
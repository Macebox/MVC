<?php

class CCIndex implements IController
{
	public function Index()
	{
		global $mvc;
		$mvc->data['title'] = "The index controller";
	}
}

?>
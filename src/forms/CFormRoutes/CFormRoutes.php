<?php/*** Controllers-specific form** @package NocturnalCore*/class CFormRoutes extends CForm implements ArrayAccess{	public function __construct($form = array(), $elements = array())	{		parent::__construct($form, $elements);	}		public function GetHTMLForElements() {    $html = '<table><tr>';	$even = 0;    foreach($this->elements as $element) {	  if (isset($element))	  {		if (get_class($element)=='CFormElementHeading')		{			$html .= '</tr></table>'.$element->GetHTML().'<table><tr><td>Enabled:</td><td>*Trigger address:</td><td>Route address:</td></tr><tr>';			$even = 0;			continue;		}		if (get_class($element)=='CFormElementSubmit')		{			$html .= '</tr></table>'.$element->GetHTML().'<table><tr>';			$even = 0;			continue;		}		if ($even==4)		{			$html .= '</tr><tr>';			$even = 0;		}		$html .= '<td>'.$element->GetHTML().'</td>';		$even++;	  }    }	$html .= '</tr></table>';    return $html;  }}
<?php
/**
 * A utility class to easy creating and handling of forms
 * 
 * @package NocturnalCore
 */
class CFormElement implements ArrayAccess{

  /**
   * Properties
   */
  public $attributes;
  

  /**
   * Constructor
   *
   * @param string name of the element.
   * @param array attributes to set to the element. Default is an empty array.
   */
  public function __construct($name, $attributes=array()) {
    $this->attributes = $attributes;    
    $this['name'] = $name;
  }
  
  
  /**
   * Implementing ArrayAccess for this->attributes
   */
  public function offsetSet($offset, $value) { if (is_null($offset)) { $this->attributes[] = $value; } else { $this->attributes[$offset] = $value; }}
  public function offsetExists($offset) { return isset($this->attributes[$offset]); }
  public function offsetUnset($offset) { unset($this->attributes[$offset]); }
  public function offsetGet($offset) { return isset($this->attributes[$offset]) ? $this->attributes[$offset] : null; }


  /**
   * Get HTML code for a element. 
   *
   * @returns HTML code for the element.
   */
  public function GetHTML() {
    $id = isset($this['id']) ? $this['id'] : 'form-element-' . $this['name'];
    $class = isset($this['class']) ? " {$this['class']}" : null;
    $validates = (isset($this['validation-pass']) && $this['validation-pass'] === false) ? ' validation-failed' : null;
    $class = (isset($class) || isset($validates)) ? " class='{$class}{$validates}'" : null;
    $name = " name='{$this['name']}'";
    $label = isset($this['label']) ? ($this['label'] . (isset($this['required']) && $this['required'] ? "<span class='form-element-required'>*</span>" : null)) : null;
    $autofocus = isset($this['autofocus']) && $this['autofocus'] ? " autofocus='autofocus'" : null;    
    $readonly = isset($this['readonly']) && $this['readonly'] ? " readonly='readonly'" : null;    
    $type 	= isset($this['type']) ? " type='{$this['type']}'" : null;
    $value 	= isset($this['value']) ? " value='{$this['value']}'" : null;
	$disabled = isset($this['disabled'])?" disabled='{$this['disabled']}'":null;
	$onclick = isset($this['onclick'])?" onclick='{$this['disabled']}'":null;

    $messages = null;
    if(isset($this['validation_messages'])) {
      $message = null;
      foreach($this['validation_messages'] as $val) {
        $message .= "<li>{$val}</li>\n";
      }
      $messages = "<ul class='validation-message'>\n{$message}</ul>\n";
    }
    
    if($type && $this['type'] == 'submit') {
      return "<input id='$id'{$type}{$class}{$name}{$value}{$autofocus}{$readonly}{$disabled}{$onclick} />\n";	
    }
	else if($type && $this['type'] == 'textarea') {
		$ret = "";
		if (!empty($label))
		{
			$ret = "<label for='$id'>$label</label><br>";
		}
		return "<p>$ret<textarea {$type}{$class}{$name}{$autofocus}{$readonly}{$disabled}>{$this['value']}</textarea></p>\n";
	}
	else if($type && $this['type'] == 'select') {
		$ret = "";
		if (!empty($label))
		{
			$ret = "<label for='$id'>$label</label><br>";
		}
		$ret = "<p>$ret<select {$class}{$name}>";
		
		if (isset($this['options']) && is_array($this['options']))
		{
			foreach($this['options'] as $opt)
			{
				$value = (is_array($opt)&&isset($opt['value']))?$opt['value']:$opt;
				$selected = (is_array($opt)&&isset($opt['selected']))?"selected='".$opt['selected']."'":null;
				$ret .= "<option {$selected}>{$value}</option>";
			}
		}
		
		$ret .= "</select></p>\n";
		return $ret;
	}
	else if($type && $this['type'] == 'checkboxgroup')
	{
		$ret = "";
		if (!empty($label))
		{
			$ret = "<p><label for='$id'>$label</label><br></p>";
		}
		
		if (isset($this['options']) && is_array($this['options']))
		{
			$columns = isset($this['columns'])?$this['columns']:3;
			$i = 0;
			$ret .= "<table>\n<tr>\n";
			foreach($this['options'] as $col)
			{
				$value		= is_array($col)											? $col['value']						: $col;
				$checked	= (is_array($col)&&isset($col['checked'])&&$col['checked'])	? "checked='".$col['checked']."'"	: null;
				$label		= (is_array($col)&&isset($col['label']))					? $col['label']						: $value;
				if ($i==$columns)
				{
					$ret .= "</tr>\n<tr>\n";
					$i=0;
				}
				$ret .= "<td><input type='checkbox' name='{$this['name']}[]' {$checked} value='{$value}' />{$label}</td>";
				$i++;
			}
			$ret .= "</table>\n";
		}
		return $ret;
	} else if ($type && $this['type']=='heading')
	{
		return "<h2>{$this['name']}</h2>\n";
	} else if ($type && $this['type']=='checkbox')
	{
		$checked = (isset($this['checked']) && $this['checked'])?" checked='checked'":null;
		$ret = "";
		if (!empty($label))
		{
			$ret = "<label for='$id'>$label</label><br>";
		}
		return "<p>$ret<input id='$id'{$type}{$class}{$name}{$value}{$autofocus}{$readonly}{$disabled}{$checked} />{$messages}</p>\n";			  
	}
	else {
	  $ret = "";
	  if (!empty($label))
	  {
		$ret = "<label for='$id'>$label</label><br>";
	  }
      return "<p>$ret<input id='$id'{$type}{$class}{$name}{$value}{$autofocus}{$readonly}{$disabled} />{$messages}</p>\n";			  
    }
  }


  /**
   * Validate the form element value according a ruleset.
   *
   * @param $rules array of validation rules.
   * returns boolean true if all rules pass, else false.
   */
  public function Validate($rules) {
    $tests = array(
      'fail' => array(
        'message' => 'Will always fail.', 
        'test' => 'return false;',
      ),
      'pass' => array(
        'message' => 'Will always pass.', 
        'test' => 'return true;',
      ),
      'not_empty' => array(
        'message' => 'Can not be empty.', 
        'test' => 'return $value != "";',
      ),
	  'numeric' => array(
		'message' => 'Must be numeric.',
		'test' => 'return is_numeric($value);',
	  ),
    );
    $pass = true;
    $messages = array();
    $value = $this['value'];
    foreach($rules as $key => $val) {
      $rule = is_numeric($key) ? $val : $key;
      if(!isset($tests[$rule])) throw new Exception('Validation of form element failed, no such validation rule exists.');
      if(eval($tests[$rule]['test']) === false) {
        $messages[] = $tests[$rule]['message'];
        $pass = false;
      }
    }
    if(!empty($messages)) $this['validation_messages'] = $messages;
    return $pass;
  }


  /**
   * Use the element name as label if label is not set.
   */
  public function UseNameAsDefaultLabel() {
    if(!isset($this['label'])) {
      $this['label'] = ucfirst(strtolower(str_replace(array('-','_'), ' ', $this['name']))).':';
    }
  }


  /**
   * Use the element name as value if value is not set.
   */
  public function UseNameAsDefaultValue() {
    if(!isset($this['value'])) {
      $this['value'] = ucfirst(strtolower(str_replace(array('-','_'), ' ', $this['name'])));
    }
  }


}


class CFormElementText extends CFormElement {
  /**
   * Constructor
   *
   * @param string name of the element.
   * @param array attributes to set to the element. Default is an empty array.
   */
  public function __construct($name, $attributes=array()) {
    parent::__construct($name, $attributes);
    $this['type'] = 'text';
	$this['class'] = 'text';
    $this->UseNameAsDefaultLabel();
  }
}

class CFormElementFile extends CFormElement {
  /**
   * Constructor
   *
   * @param string name of the element.
   * @param array attributes to set to the element. Default is an empty array.
   */
  public function __construct($name, $attributes=array()) {
    parent::__construct($name, $attributes);
    $this['type'] = 'file';
	$this['class'] = 'file';
    $this->UseNameAsDefaultLabel();
  }
}

class CFormElementHidden extends CFormElement {
  /**
   * Constructor
   *
   * @param string name of the element.
   * @param array attributes to set to the element. Default is an empty array.
   */
  public function __construct($name, $attributes=array()) {
    parent::__construct($name, $attributes);
    $this['type'] = 'hidden';
	$this['label']	= '';
    $this->UseNameAsDefaultLabel();
  }
}


class CFormElementPassword extends CFormElement {
  /**
   * Constructor
   *
   * @param string name of the element.
   * @param array attributes to set to the element. Default is an empty array.
   */
  public function __construct($name, $attributes=array()) {
    parent::__construct($name, $attributes);
    $this['type'] = 'password';
	$this['class'] = 'text';
    $this->UseNameAsDefaultLabel();
  }
}


class CFormElementSubmit extends CFormElement {
  /**
   * Constructor
   *
   * @param string name of the element.
   * @param array attributes to set to the element. Default is an empty array.
   */
  public function __construct($name, $attributes=array()) {
    parent::__construct($name, $attributes);
    $this['type'] = 'submit';
	$this['class'] = 'button';
    $this->UseNameAsDefaultValue();
  }
}

class CFormElementTextArea extends CFormElement {
  /**
   * Constructor
   *
   * @param string name of the element.
   * @param array attributes to set to the element. Default is an empty array.
   */
  public function __construct($name, $attributes=array()) {
    parent::__construct($name, $attributes);
    $this['type'] = 'textarea';
    $this->UseNameAsDefaultLabel();
  }
}

class CFormElementSelect extends CFormElement
{
	public function __construct($name, $attributes=array())
	{
		parent::__construct($name, $attributes);
		$this['type'] = 'select';
		$this->UseNameAsDefaultLabel();
	}
}

class CFormElementCheckboxGroup extends CFormElement
{
	public function __construct($name, $attributes=array())
	{
		parent::__construct($name, $attributes);
		$this['type'] = 'checkboxgroup';
		$this->UseNameAsDefaultLabel();
	}
}

class CFormElementCheckbox extends CFormElement
{
	public function __construct($name, $attributes=array())
	{
		parent::__construct($name, $attributes);
		$this['type'] = 'checkbox';
		$this->UseNameAsDefaultLabel();
	}
}

class CFormElementHeading extends CFormElement
{
	public function __construct($name, $attributes=array())
	{
		parent::__construct($name, $attributes);
		$this['type'] = 'heading';
		$this->UseNameAsDefaultLabel();
	}
}

class CFormElementEditContent extends CFormElement
{
	public function __construct($name, $attributes=array())
	{
		parent::__construct($name, $attributes);
		$this['type'] = 'textarea';
		$this->UseNameAsDefaultLabel();
	}
	
	public function GetHTML() {
    $id = isset($this['id']) ? $this['id'] : 'form-element-' . $this['name'];
    $class = isset($this['class']) ? " {$this['class']}" : null;
    $validates = (isset($this['validation-pass']) && $this['validation-pass'] === false) ? ' validation-failed' : null;
    $class = (isset($class) || isset($validates)) ? " class='{$class}{$validates}'" : null;
    $name = " name='{$this['name']}'";
    $label = isset($this['label']) ? ($this['label'] . (isset($this['required']) && $this['required'] ? "<span class='form-element-required'>*</span>" : null)) : null;
    $autofocus = isset($this['autofocus']) && $this['autofocus'] ? " autofocus='autofocus'" : null;    
    $readonly = isset($this['readonly']) && $this['readonly'] ? " readonly='readonly'" : null;    
    $type 	= isset($this['type']) ? " type='{$this['type']}'" : null;
    $value 	= isset($this['value']) ? " value='{$this['value']}'" : null;
	$disabled = isset($this['disabled'])?" disabled='{$this['disabled']}'":null;
	$onclick = isset($this['onclick'])?" onclick='{$this['disabled']}'":null;

    $messages = null;
    if(isset($this['validation_messages'])) {
      $message = null;
      foreach($this['validation_messages'] as $val) {
        $message .= "<li>{$val}</li>\n";
      }
      $messages = "<ul class='validation-message'>\n{$message}</ul>\n";
    }
		$ret = "<p><label for='$id'>$label</label><br>";
		$bbcode = array(
			'Bold'		=> 'b',
			'Italic'	=> 'i',
			'Underline'	=> 'u',
			'Img'		=> 'img',
			'Url'		=> 'url',
			'Url='		=> 'url=',
			'Quote'		=> 'quote',
			'Code'		=> 'code',
			'Size'		=> 'size',
			'Color'		=> 'color',
			);
		
		foreach($bbcode as $key => $value)
		{
			$ret .= "<input type='button' class='button' onclick=\"javascript:bbcode_ins('$id', '{$value}');\" value='{$key}'>";
		}
		
		$ret .= "<br>";
		
		$ret .= "<textarea id='$id'{$type}{$class}{$name}{$autofocus}{$readonly}{$disabled}>{$this['value']}</textarea></p>\n";
		return $ret;
  }
}

/**
* Class for creation of forms and handling their validation.
*
* @package NocturnalCMF
*/

class CForm implements ArrayAccess {

  /**
   * Properties
   */
  public $form;     // array with settings for the form
  public $elements; // array with all form elements
  

  /**
   * Constructor
   */
  public function __construct($form=array(), $elements=array()) {
    $this->form = $form;
    $this->elements = $elements;
  }


  /**
   * Implementing ArrayAccess for this->elements
   */
  public function offsetSet($offset, $value) { if (is_null($offset)) { $this->elements[] = $value; } else { $this->elements[$offset] = $value; }}
  public function offsetExists($offset) { return isset($this->elements[$offset]); }
  public function offsetUnset($offset) { unset($this->elements[$offset]); }
  public function offsetGet($offset) { return isset($this->elements[$offset]) ? $this->elements[$offset] : null; }


  /**
   * Add a form element
   *
   * @param $element CFormElement the formelement to add.
   * @returns $this CForm
   */
  public function AddElement($element) {
    $this[$element['name']] = $element;
    return $this;
  }
  
  /**
   * Get the value of a element
   */
  public function GetValue($key) {
    return (isset($_POST[$key])) ? $_POST[$key] : null;
  }
  

  /**
   * Set validation to a form element
   *
   * @param $element string the name of the formelement to add validation rules to.
   * @param $rules array of validation rules.
   * @returns $this CForm
   */
  public function SetValidation($element, $rules) {
    $this[$element]['validation'] = $rules;
    return $this;
  }
  

  /**
   * Return HTML for the form or the formdefinition.
   *
   * @param $type string what part of the form to return.
   * @returns string with HTML for the form.
   */
  public function GetHTML($type=null) {
    $id 	  = isset($this->form['id'])      ? " id='{$this->form['id']}'" : null;
    $class 	= isset($this->form['class'])   ? " class='{$this->form['class']}'" : null;
    $name 	= isset($this->form['name'])    ? " name='{$this->form['name']}'" : null;
    $action = isset($this->form['action'])  ? " action='{$this->form['action']}'" : null;
	$enctype = isset($this->form['enctype'])  ? " enctype='{$this->form['enctype']}'" : null;
    $method = " method='post'";

    if($type == 'form') {
      return "<form{$id}{$enctype}{$class}{$name}{$action}{$method}>";
    }
    
    $elements = $this->GetHTMLForElements();
    $html = <<< EOD
\n<form{$id}{$enctype}{$class}{$name}{$action}{$method}>
<fieldset>
{$elements}
</fieldset>
</form>
EOD;
    return $html;
  }
 

  /**
   * Return HTML for the elements
   */
  public function GetHTMLForElements() {
    $html = null;
    $buttonbar = null;
    foreach($this->elements as $element) {
	  if (isset($element))
	  {
		if(!$buttonbar && $element['type'] == 'submit') {
          $buttonbar = true;
          $html .= "<p>";
        } else if($buttonbar && $element['type'] != 'submit') {
          $buttonbar = false;
          $html .= "</p>\n";
        }
		$html .= $element->GetHTML();
	  }
    }
	if ($buttonbar)
	{
		$html .= "</p>\n";
	}
    return $html;
  }
  

  /**
   * Check if a form was submitted and perform validation and call callbacks.
   *
   * The form is stored in the session if validation fails. The page should then be redirected
   * to the original form page, the form will populate from the session and should then be 
   * rendered again.
   *
   * @returns boolean true if validates, false if not validate, null if not submitted.
   */
  public function Check() {
    $validates = null;
    $callbackStatus = null;
    $values = array();
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      unset($_SESSION['form-failed']);
      $validates = true;
      foreach($this->elements as $element) {
        if(isset($_POST[$element['name']])) {
          $values[$element['name']]['value'] = $element['value'] = $_POST[$element['name']];
          if(isset($element['validation'])) {
            $element['validation-pass'] = $element->Validate($element['validation']);
            if($element['validation-pass'] === false) {
              $values[$element['name']] = array('value'=>$element['value'], 'validation-messages'=>$element['validation-messages']);
              $validates = false;
            }
          }
          if(isset($element['callback']) && $validates) {
            if(isset($element['callback-args'])) {
     if(call_user_func_array($element['callback'], array_merge(array($this), $element['callback-args'])) === false) {
     $callbackStatus = false;
     }
   } else {
              if(call_user_func($element['callback'], $this) === false) {
     $callbackStatus = false;
              }
            }
          }
        }
      }
    } else if(isset($_SESSION['form-failed'])) {
      foreach($_SESSION['form-failed'] as $key => $val) {
        $this[$key]['value'] = $val['value'];
        if(isset($val['validation-messages'])) {
          $this[$key]['validation-messages'] = $val['validation-messages'];
          $this[$key]['validation-pass'] = false;
        }
      }
      unset($_SESSION['form-failed']);
    }
    if($validates === false || $callbackStatus === false) {
      $_SESSION['form-failed'] = $values;
    }
    if($callbackStatus === false)
      return false;
    else
      return $validates;
  }
  
  
}
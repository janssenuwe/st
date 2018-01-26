<?php
namespace Homepage\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class SearchWordFilter extends InputFilter
{
	public function __construct()
	{
            
        $this->add(array(
			'name'     => 'wort_name',
			'required' => false,
			'filters'  => array(
				array('name' => 'StripTags'),
				array('name' => 'StringTrim'),
			),
		));
		
		$this->add(array(
			'name'     => 'wort_ersatz',
			'required' => false,
			'filters'  => array(
				array('name' => 'StripTags'),
				array('name' => 'StringTrim'),
			),
		));
            
		$this->add(array(
			'name'     => 'wort_bearbeitet',
			'required' => false,
			'filters'  => array(
				array('name' => 'StripTags'),
				array('name' => 'StringTrim'),
			),
		));
            
	}
}

<?php
namespace Homepage\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class WordFilter extends InputFilter
{
	public function __construct()
	{
            
            
            
        $this->add(array(
        'name'     => 'name',
        'required' => false,
        'filters'  => array(
            array('name' => 'StripTags'),
            array('name' => 'StringTrim'),
        ),
        
        ));
        
        $this->add(array(
        'name'     => 'ersatz',
        'required' => false,
        'filters'  => array(
            array('name' => 'StripTags'),
            array('name' => 'StringTrim'),
        ),
                   
        ));
    }
}

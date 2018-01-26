<?php
namespace Auth\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Digits;

class CompleteRegistrationFilter extends InputFilter
{
	public function __construct($sm)
	{
        $this->add(array(
			'name'     => 'user_strasse',
			'required' => true,
			'filters'  => array(
				array('name' => 'StripTags'),
				array('name' => 'StringTrim'),
			),
			'validators' => array(
				array(
					'name'    => 'StringLength',
					'options' => array(
						'encoding' => 'UTF-8',
						'min'      => 2,
						'max'      => 50,
					    'messages' => array(
                            'stringLengthTooShort' => '',
                            'stringLengthTooLong' => ''
                        ),
                    ),
                ),
			    array(
			        'name' =>'NotEmpty',
			        'options' => array(
			            'messages' => array(
			                \Zend\Validator\NotEmpty::IS_EMPTY => '!Straße'
			            ),
			        ),
			    ),
			),
		));
            
        $this->add(array(
			'name'     => 'user_hausnummer',
			'required' => true,
			'filters'  => array(
				array('name' => 'StripTags'),
				array('name' => 'StringTrim'),
			),
			'validators' => array(
				array(
					'name'         => 'StringLength',
					'options'      => array(
					    'encoding' => 'UTF-8',
					    'max'      => 5,
                        'min'      => 1,
					    'messages' => array( 
                                'stringLengthTooShort'   => '!Haus-Nr.',
                                'stringLengthTooLong'   => '!Haus-Nr.'
                        ),
                    ),
				),
    			array(
                    'name' =>'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\NotEmpty::IS_EMPTY => ' '
                        ),
                    ),
                ),
            ),
		));
            
        $this->add(array(
			'name'     => 'user_plz',
			'required' => true,
			'filters'  => array(
				array('name' => 'StripTags'),
				array('name' => 'StringTrim'),
			),
			'validators' => array(
				array(
					'name'    => 'StringLength',
					'options' => array(
						'encoding' => 'UTF-8',
						'min'      => 3,
						'max'      => 10,
					    'messages' => array(
                            'stringLengthTooShort' => '',
                            'stringLengthTooLong' => ''
                        ),
                    ),
				),
    		    array(
                    'name' =>'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\NotEmpty::IS_EMPTY => '!PLZ'
                        ),
                    ),
                ),  
		    ),
            
		));
            
        $this->add(array(
			'name'     => 'user_ort',
			'required' => true,
			'filters'  => array(
				array('name' => 'StripTags'),
				array('name' => 'StringTrim'),
			),
			'validators' => array(
				array(
					'name'    => 'StringLength',
					'options' => array(
						'encoding' => 'UTF-8',
						'min'      => 2,
						'max'      => 50,
					    'messages' => array(
                            'stringLengthTooShort' => '',
                            'stringLengthTooLong' => ''
                        ),
                    ),
				),
    			array(
                    'name' =>'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\NotEmpty::IS_EMPTY => '!Ort'
                        ),
                    ),
                ),
		    ),
            
		));

        $this->add(array(
            'name'     => 'user_land',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' =>'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\NotEmpty::IS_EMPTY => '!Land'
                        ),
                    ),
                ),
            )
        ));

        $this->add(array(
			'name'     => 'user_laendervorwahl',
			'required' => true,
			'filters'  => array(
				array('name' => 'StripTags'),
				array('name' => 'StringTrim'),
			),
            'validators' => array(
                array(
                    'name' =>'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\NotEmpty::IS_EMPTY => '!Ländervorwahl'
                        ),
                    ),
                ),
            )
		));
                
        $this->add(array(
			'name'     => 'user_vorwahl',
			'required' => true,
			'filters'  => array(
				array('name' => 'StripTags'),
				array('name' => 'StringTrim'),
			),
			'validators' => array(
				array(
					'name'    => 'StringLength',
					'options' => array(
						'encoding' => 'UTF-8',
						'min'      => 2,
						'max'      => 6,
					    'messages' => array(
                            'stringLengthTooShort' => '',
                            'stringLengthTooLong' => ''
                        ),
                    ),
    				
			    ),
			    array(
                    'name' =>'NotEmpty',
                    'options' => array(
                        'messages' => array(
                             \Zend\Validator\NotEmpty::IS_EMPTY => '!Vorwahl'
                        ),
                    ),
                ),            
			),
		));
               
        $this->add(array(
			'name'     => 'user_telefon',
			'required' => true,
			'filters'  => array(
				array('name' => 'StripTags'),
				array('name' => 'StringTrim'),
			),
			'validators' => array(
				array(
					'name'    => 'StringLength',
					'options' => array(
						'encoding' => 'UTF-8',
						'min'      => 3,
						'max'      => 20,
					    'messages' => array(
                            'stringLengthTooShort' => '',
                            'stringLengthTooLong' => ''
                        ),
                    ),
				),
    			array(
                    'name' =>'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\NotEmpty::IS_EMPTY => '!Telefonnummer'
                        ),
                    ),
                ),
		    ),
                     
		));

        $this->add(array(
            'name' => 'user_agb',
            'validators' => array(
                array(
                    'name' => 'Digits',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            Digits::NOT_DIGITS => 'Bitte lesen und akzeptieren Sie die AGB und Datenschutzbestimmungen'
                        ),
                    ),
                ),
            ),
        ));
/*
        $this->add(array(
            'name' => 'user_datenschutz',
            'validators' => array(
                array(
                    'name' => 'Digits',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            Digits::NOT_DIGITS => 'Bestätigen Sie bitte die Datenschutzerklärung'
                        ),
                    ),
                ),
            ),
        ));
*/
	}
}
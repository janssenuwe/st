<?php
namespace Auth\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Digits;

class RegistrationFilter extends InputFilter
{
	public function __construct($sm)
	{
            
            
            
        $this->add(array(
			'name'     => 'user_firmenname',
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
                            \Zend\Validator\NotEmpty::IS_EMPTY => '!Firma/Organisation'
                        ),
                    ),
				),
			),
		));
        $this->add(array(
			'name'     => 'user_anrede',
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
                            \Zend\Validator\NotEmpty::IS_EMPTY => '!Anrede'
                        ),
                    ),
                ),
			),
		));
        $this->add(array(
			'name'     => 'user_titel',
			'required' => false,
			'filters'  => array(
				array('name' => 'StripTags'),
				array('name' => 'StringTrim'),
            ),
		));
        $this->add(array(
			'name'     => 'user_vorname',
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
						'max'      => 30,
                        'messages' => array(
                            'stringLengthTooShort'  => '!Vorname',
                            'stringLengthTooLong'   => '!Vorname'
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
			'name'     => 'user_nachname',
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
                        'max'      => 30,
                        'messages' => array(
                            'stringLengthTooShort' => '!Nachname',
                            'stringLengthTooLong' => '!Nachname'
                        ),
                    ),
				),
                array(
                    'name'      =>'NotEmpty',
                    'options'   => array(
                        'messages' => array(
                            \Zend\Validator\NotEmpty::IS_EMPTY => ' '
                        ),
                    ),
                ),
			),
		));
        $this->add(array(
			'name'     => 'user_email',
			'required' => true,
			'filters'  => array(
				array('name' => 'StripTags'),
				array('name' => 'StringTrim'),
			),
			'validators' => array(
                array(
                    'name' => 'EmailAddress',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'messages' => array(
                            \Zend\Validator\EmailAddress::INVALID_FORMAT => '!E-Mail Adresse'
                        )
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
                array(
					'name'		    => 'Zend\Validator\Db\NoRecordExists',
					'options'       => array(
						'table'     => 'users',
						'field'     => 'user_email',
						'adapter'   => $sm->get('Zend\Db\Adapter\Adapter'),
                        'messages'  => array(
                            \Zend\Validator\Db\NoRecordExists::ERROR_RECORD_FOUND => 'Ein Account mit dieser E-Mail Adresse existiert bereits'
                        ),
					),
				),
			),
		));
            
        $this->add(array(
			'name'     => 'user_passwort',
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
						'min'      => 6,
						'max'      => 50,
					'messages' => array(
                                                'stringLengthTooShort' => '!Passwort (mind. 6 Zeichen)',
                                                'stringLengthTooLong' => '!Passwort (max. 50 Zeichen)'
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
//                                array( 
//                                       // 'name' => 'identical', 
//                                        'options' => array('token' => 'password' ) 
//                                ), 
			),
		));
	}
}
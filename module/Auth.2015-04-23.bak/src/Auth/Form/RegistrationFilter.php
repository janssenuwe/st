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
                            \Zend\Validator\NotEmpty::IS_EMPTY => 'Bitte geben Sie einen Firmennamen ein'
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
                                              \Zend\Validator\NotEmpty::IS_EMPTY => 'Wählen Sie eine Anrede' 
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
                                                    'stringLengthTooShort' => 'Vorname muss mindestens 2 Zeichen lang sein', 
                                                    'stringLengthTooLong' => 'Vorname darf maximal 30 Zeichen lang sein' 
                                                ),
					),
				),
                                array(
                                        'name' =>'NotEmpty', 
                                          'options' => array(
                                              'messages' => array(
                                                  \Zend\Validator\NotEmpty::IS_EMPTY => 'Bitte geben Sie einen Vornamen ein' 
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
                                                'stringLengthTooShort' => 'Nachname muss mindestens 2 Zeichen lang sein', 
                                                'stringLengthTooLong' => 'Nachname darf maximal 30 Zeichen lang sein' 
                                            ),
                                    ),
				),
                                array(
                                    'name' =>'NotEmpty', 
                                      'options' => array(
                                          'messages' => array(
                                              \Zend\Validator\NotEmpty::IS_EMPTY => 'Bitte geben Sie einen Nachnamen ein' 
                                        ),
                                    ),
                                ),
			),
		));
        /*
        $this->add(array(
			'name'     => 'user_land',
			'required' => false,
			'filters'  => array(
				array('name' => 'StripTags'),
				array('name' => 'StringTrim'),
			),
		));
         
            
        $this->add(array(
			'name'     => 'user_strasse',
			'required' => false,
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
                                                'stringLengthTooShort' => 'Straße muss mindestens 2 Zeichen lang sein', 
                                                'stringLengthTooLong' => 'Straße darf maximal 50 Zeichen lang sein' 
                                        ),
                                    ),
                ),
			),
		));
            
            $this->add(array(
			'name'     => 'user_hausnummer',
			'required' => false,
			'filters'  => array(
				array('name' => 'StripTags'),
				array('name' => 'StringTrim'),
			),
			'validators' => array(
				array(
					'name'    => 'StringLength',
					'options' => array(
						'encoding' => 'UTF-8',
						'max'      => 5,
					'messages' => array( 
                                                'stringLengthTooLong' => 'Hausnummer darf maximal 5 Zeichen lang sein' 
                                            ),
                                    ),
				),
			),
		));
            
            $this->add(array(
			'name'     => 'user_plz',
			'required' => false,
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
                                                'stringLengthTooShort' => 'Postleitzahl muss mindestens 3 Zeichen lang sein', 
                                                'stringLengthTooLong' => 'Postleitzahl darf maximal 10 Zeichen lang sein' 
                                            ),
                                    ),
				),
			),
		));
            
            $this->add(array(
			'name'     => 'user_ort',
			'required' => false,
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
                                                'stringLengthTooShort' => 'Ort muss mindestens 2 Zeichen lang sein', 
                                                'stringLengthTooLong' => 'Ort darf maximal 50 Zeichen lang sein' 
                                            ),
                                    ),
				),
			),
		));
        */    
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
                                            \Zend\Validator\EmailAddress::INVALID_FORMAT => 'Keine gültige E-Mail Adresse' 
                                        ) 
                                    ), 
                                ),
                                array(
                                    'name' =>'NotEmpty', 
                                      'options' => array(
                                          'messages' => array(
                                              \Zend\Validator\NotEmpty::IS_EMPTY => 'Bitte geben Sie eine gültige E-Mail Adresse ein' 
                                        ),
                                    ),
                                ),
                                array(
					'name'		=> 'Zend\Validator\Db\NoRecordExists',
					'options' => array(
						'table'   => 'users',
						'field'   => 'user_email',
						'adapter' => $sm->get('Zend\Db\Adapter\Adapter'),
                                                'messages' => array(
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
                                                'stringLengthTooShort' => 'Passwort muss mindestens 6 Zeichen lang sein', 
                                                'stringLengthTooLong' => 'Passwort darf maximal 50 Zeichen lang sein' 
                                            ),
                                    ),
				),
                                array(
                                    'name' =>'NotEmpty', 
                                      'options' => array(
                                          'messages' => array(
                                              \Zend\Validator\NotEmpty::IS_EMPTY => 'Bitte geben Sie ein Passwort ein' 
                                        ),
                                    ),
                                ),
//                                array( 
//                                       // 'name' => 'identical', 
//                                        'options' => array('token' => 'password' ) 
//                                ), 
			),
		));
                
           /*      $this->add(array(
			'name'     => 'user_laendervorwahl',
			'required' => false,
			'filters'  => array(
				array('name' => 'StripTags'),
				array('name' => 'StringTrim'),
			),
		));
                
                $this->add(array(
			'name'     => 'user_vorwahl',
			'required' => false,
			'filters'  => array(
				array('name' => 'StripTags'),
				array('name' => 'StringTrim'),
			),
			'validators' => array(
				array(
					'name'    => 'StringLength',
					'options' => array(
						'encoding' => 'UTF-8',
						'min'      => 4,
						'max'      => 6,
					'messages' => array(
                                                'stringLengthTooShort' => 'Vorwahl muss mindestens 4 Zeichen lang sein', 
                                                'stringLengthTooLong' => 'Vorwahl darf maximal 6 Zeichen lang sein' 
                                        ),
                                    ),
				),
			),
		));
               
        $this->add(array(
			'name'     => 'user_telefon',
			'required' => false,
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
                                                'stringLengthTooShort' => 'Telefonnummer muss mindestens 3 Zeichen lang sein', 
                                                'stringLengthTooLong' => 'Telefonnummer darf maximal 20 Zeichen lang sein' 
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
                                        Digits::NOT_DIGITS => 'Bestätigen Sie bitte die AGBs'
                                    ),
                                ),
                            ),
                        ),
		));
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
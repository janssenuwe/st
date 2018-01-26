<?php
namespace Homepage\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class UserFilter extends InputFilter
{
	public function __construct()
	{
            
            
            
            $this->add(array(
			'name'     => 'user_firmenname',
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
						'max'      => 20,
                                                'messages' => array(
                                                    'stringLengthTooShort' => 'Firmenname muss mindestens 2 Zeichen lang sein', 
                                                    'stringLengthTooLong' => 'Firmenname darf maximal 20 Zeichen lang sein' 
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
			),
		));
            
                $this->add(array(
			'name'     => 'user_passwort',
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
                
                $this->add(array(
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
						'min'      => 2,
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

                
            
//		// self::__construct(); // parnt::__construct(); - trows and error
//		$this->add(array(
//			'name'     => 'usr_name',
//			'required' => true,
//			'filters'  => array(
//				array('name' => 'StripTags'),
//				array('name' => 'StringTrim'),
//			),
//			'validators' => array(
//				array(
//					'name'    => 'StringLength',
//					'options' => array(
//						'encoding' => 'UTF-8',
//						'min'      => 1,
//						'max'      => 100,
//					),
//				),
//				array(
//					'name'		=> 'Zend\Validator\Db\NoRecordExists',
//					'options' => array(
//						'table'   => 'users',
//						'field'   => 'usr_name',
//						'adapter' => $sm->get('Zend\Db\Adapter\Adapter'),
//					),
//				),
//			),
//		));
//
//        $this->add(array(
//            'name'       => 'usr_email',
//            'required'   => true,
//            'validators' => array(
//                array(
//                    'name' => 'EmailAddress'
//                ),
//				array(
//					'name'		=> 'Zend\Validator\Db\NoRecordExists',
//					'options' => array(
//						'table'   => 'users',
//						'field'   => 'usr_email',
//						'adapter' => $sm->get('Zend\Db\Adapter\Adapter'),
//					),
//				),
//            ),
//        ));
//		
//		$this->add(array(
//			'name'     => 'usr_password',
//			'required' => true,
//			'filters'  => array(
//				array('name' => 'StripTags'),
//				array('name' => 'StringTrim'),
//			),
//			'validators' => array(
//				array(
//					'name'    => 'StringLength',
//					'options' => array(
//						'encoding' => 'UTF-8',
//						'min'      => 6,
//						'max'      => 12,
//					),
//				),
//			),
//		));	
//
//		$this->add(array(
//			'name'     => 'usr_password_confirm',
//			'required' => true,
//			'filters'  => array(
//				array('name' => 'StripTags'),
//				array('name' => 'StringTrim'),
//			),
//			'validators' => array(
//				array(
//					'name'    => 'StringLength',
//					'options' => array(
//						'encoding' => 'UTF-8',
//						'min'      => 6,
//						'max'      => 12,
//					),
//				),
//                array(
//                    'name'    => 'Identical',
//                    'options' => array(
//                        'token' => 'usr_password',
//                    ),
//                ),
//			),
//		));		
	}
}
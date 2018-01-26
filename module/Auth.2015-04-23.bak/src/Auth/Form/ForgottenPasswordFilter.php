<?php
namespace Auth\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class ForgottenPasswordFilter extends InputFilter
{
	public function __construct($sm)
	{
        $this->add(array(
            'name'       => 'user_email',
            'required'   => true,
            'validators' => array(
                array(
                    'name' => 'EmailAddress',
                ),
				array(
					'name'		=> 'Zend\Validator\Db\RecordExists',
					'options' => array(
						'table'   => 'users',
						'field'   => 'user_email',
						'adapter' => $sm->get('Zend\Db\Adapter\Adapter'),
					),
				),
                array(
                    'name' =>'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\NotEmpty::IS_EMPTY              => 'Bitte geben Sie eine E-Mail Adresse an',
                        ),
                    ),
                ),
            ),
        ));	
	}
}
<?php
namespace Auth\Form;

use Zend\Form\Form;

class AuthForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('auth');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'user_email',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'user_passwort',
            'attributes' => array(
                'type'  => 'password',
                'class' => 'form-control'
            ),
        ));
		
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
                'class' => 'btn btn-default'
            ),
        )); 
    }
}
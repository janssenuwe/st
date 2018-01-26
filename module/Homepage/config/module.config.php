<?php
return array(
//	'static_salt' => 'aFGQ475SDsdfsaf2342', // I am going to move it to global.php. It should be accessable everywhere
	'controllers' => array(
        'invokables' => array(
            'Homepage\Controller\Index'             => 'Homepage\Controller\IndexController',
            'Homepage\Controller\Admin'             => 'Homepage\Controller\AdminController',
            'Homepage\Controller\User'              => 'Homepage\Controller\UserController',
            'Homepage\Controller\BehaviorProfiles'  => 'Homepage\Controller\BehaviorProfilesController',
        ),
	),
    'router' => array(
        'routes' => array(
			'homepage' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/homepage',
					'defaults' => array(
						'__NAMESPACE__' => 'Homepage\Controller',
						'controller'    => 'Index',
						'action'        => 'index',
                        'id'            => 0,

					),
				),
				'may_terminate' => true,
				'child_routes' => array(
					'default' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'    => '/[:controller[/:action[/:id]]]',
							'constraints' => array(
								'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
								'id'     	 => '[a-zA-Z0-9_?=]*',
					
							),
							'defaults' => array(
							),
							
						),
					),
				),
			),			
		),
	),
    'view_manager' => array(
//        'template_map' => array(
//            'layout/Auth'           => __DIR__ . '/../view/layout/Auth.phtml',
//        ),
        'template_path_stack' => array(
            'homepage' => __DIR__ . '/../view'
        ),
		
		'display_exceptions' => true,
    ),

);
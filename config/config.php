<?php
return [
    'dependencies' => [
        'factories' => [
            Soliant\FormidableBootstrap\Extension\Input::class =>
                Soliant\FormidableBootstrap\Extension\InputFactory::class,
            'Soliant.FormidableBootstrap.ErrorFormatter' => Soliant\FormidableBootstrap\ErrorFormatterFactory::class,
        ],
    ],

    'plates' => [
        'extensions' => [
            Soliant\FormidableBootstrap\Extension\Input::class,
        ],
    ],
];

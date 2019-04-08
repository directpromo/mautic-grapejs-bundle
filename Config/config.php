<?php

/*
 * @copyright   2019 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

return [
    'name'        => 'Mautic GrapeJS Bundle',
    'description' => 'GrapeJS integration for Mautic',
    'author'      => 'mtcextendee.com',
    'version'     => '1.0.0',
    'services' => [
        'events'  => [
            'mautic.grape.js.asset.subscriber'=>[
                'class'=> \MauticPlugin\MauticGrapeJsBundle\EventListener\AssetSubscriber::class,
            ]
        ],
        'forms'   => [
        ],
        'helpers' => [],
        'other'   => [
        ],
        'models'       => [],
        'integrations' => [
            'mautic.integration.grapejs' => [
                'class' => \MauticPlugin\MauticGrapeJsBundle\Integration\GrapeJsIntegration::class,
            ],
        ],
    ],
    'routes'     => [
        'main' => [
            'mautic_grapejs_action' => [
                'path'       => '/grapejs/{objectAction}/{objectId}',
                'controller' => 'MauticGrapeJsBundle:GrapeJs:execute',
            ],
        ],
    ],
    'menu'       => [],
    'parameters' => [
    ],
];

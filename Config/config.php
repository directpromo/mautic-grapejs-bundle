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
                'arguments' => [
                    'mautic.helper.integration'
                ],
            ],
        ],
        'forms'   => [
        ],
        'helpers' => [],
        'other'   => [
            'mautic.grape.js.uploader' => [
                'class'     => MauticPlugin\MauticGrapeJsBundle\Uploader\GrapeJsUploader::class,
                'arguments' => [
                    'mautic.helper.file_uploader',
                    'mautic.helper.core_parameters',
                    'mautic.helper.paths',
                ],
            ],
        ],
        'models'       => [],
        'integrations' => [
            'mautic.integration.grapejs' => [
                'class' => \MauticPlugin\MauticGrapeJsBundle\Integration\GrapeJsIntegration::class,
            ],
        ],
    ],
    'routes'     => [
        'public' => [
            'mautic_grapejs_upload' => [
                'path'       => '/grapesjs/upload',
                'controller' => 'MauticGrapeJsBundle:Ajax:upload',
            ],
        ],
        'main' => [
            'mautic_grapejs_action' => [
                'path'       => '/grapejs/{objectType}/builder/{objectId}',
                'controller' => 'MauticGrapeJsBundle:GrapeJs:builder',
            ],
        ],
    ],
    'menu'       => [],
    'parameters' => [
        'grapes_js_image_directory'=> 'grapesjs'
    ],
];

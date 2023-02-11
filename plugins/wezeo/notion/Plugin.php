<?php namespace Wezeo\Notion;

use Backend;
use System\Classes\PluginBase;

/**
 * notion Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'notion',
            'description' => 'No description provided yet...',
            'author'      => 'wezeo',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {

    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return []; // Remove this line to activate

        return [
            'Wezeo\Notion\Components\MyComponent' => 'myComponent',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'wezeo.notion.some_permission' => [
                'tab' => 'notion',
                'label' => 'Some permission'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return []; // Remove this line to activate

        return [
            'notion' => [
                'label'       => 'notion',
                'url'         => Backend::url('wezeo/notion/mycontroller'),
                'icon'        => 'icon-leaf',
                'permissions' => ['wezeo.notion.*'],
                'order'       => 500,
            ],
        ];
    }
}

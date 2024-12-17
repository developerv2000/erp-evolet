<?php

/**
 * @author Bobur Nuridinov <developerv2000@gmail.com>
 */

namespace App\Support\Generators;

use Illuminate\Support\Facades\Route;

/**
 * Class CrudRouteGenerator
 *
 * This class provides helper methods for generating default CRUD routes
 * inside grouped routes with a defined controller, prefix, and name.
 * It also supports adding a custom method prefix for specialized routes (e.g., dashboard routes).
 */
class CrudRouteGenerator
{
    /**
     * Get all default route names.
     *
     * @return array The array of default CRUD route names.
     */
    private static function getDefaultRouteNames()
    {
        return [
            'index',
            'create',
            'show',
            'edit',
            'trash',
            'store',
            'update',
            'destroy',
            'restore',
        ];
    }

    /**
     * Define a specific CRUD route by its name.
     *
     * @param string $name The name of the route to define.
     * @param string $identifierAttribute The attribute used for identifying records in the 'show' route (e.g., 'id' or 'slug').
     * @param string|null $methodPrefix Optional controller method prefix for specialized routes (e.g., 'dashboard').
     * @return void
     */
    public static function defineRouteByName($name, $identifierAttribute = 'id', $methodPrefix = null)
    {
        // Add the method prefix to the controller action if provided.
        $controllerAction = $methodPrefix ? $methodPrefix . ucfirst($name) : $name;

        // Define routes based on the provided name and identifier attribute.
        switch ($name) {
            case 'index':
                Route::get('/', $controllerAction)->name('index');
                break;
            case 'trash':
                Route::get('/trash', $controllerAction)->name('trash');
                break;
            case 'create':
                Route::get('/create', $controllerAction)->name('create');
                break;
            case 'show':
                Route::get('/{record:' . $identifierAttribute . '}', $controllerAction)->name('show');
                break;
            case 'edit':
                Route::get('/edit/{record:' . $identifierAttribute . '}', $controllerAction)->name('edit');
                break;
            case 'store':
                Route::post('/store', $controllerAction)->name('store');
                break;
            case 'update':
                Route::patch('/update/{record}', $controllerAction)->name('update');
                break;
            case 'destroy':
                Route::delete('/destroy', $controllerAction)->name('destroy');
                break;
            case 'restore':
                Route::patch('/restore', $controllerAction)->name('restore');
                break;
        }
    }

    /**
     * Define all default CRUD routes.
     *
     * @param string $identifierAttribute The attribute used for identifying records in the 'show' route (e.g., 'id' or 'slug').
     * @param string|null $methodPrefix Optional controller method prefix for specialized routes (e.g., 'dashboard').
     * @return void
     */
    public static function defineAllDefaultRoutes($identifierAttribute = 'id', $methodPrefix = null)
    {
        $defaultRoutes = self::getDefaultRouteNames();

        foreach ($defaultRoutes as $route) {
            self::defineRouteByName($route, $identifierAttribute, $methodPrefix);
        }
    }

    /**
     * Define default CRUD routes, excluding specified routes.
     *
     * @param array $excepts The routes to exclude from the definition.
     * @param string $identifierAttribute The attribute used for identifying records in the 'show' route (e.g., 'id' or 'slug').
     * @param string|null $methodPrefix Optional controller method prefix for specialized routes (e.g., 'dashboard').
     * @return void
     */
    public static function defineDefaultRoutesExcept($excepts = [], $identifierAttribute = 'id', $methodPrefix = null)
    {
        $defaultRoutes = self::getDefaultRouteNames();

        // Filter out the excluded routes
        $routes = array_diff($defaultRoutes, $excepts);

        foreach ($routes as $route) {
            self::defineRouteByName($route, $identifierAttribute, $methodPrefix);
        }
    }

    /**
     * Define only specific CRUD routes.
     *
     * @param array $only The routes to include in the definition.
     * @param string $identifierAttribute The attribute used for identifying records in the 'show' route (e.g., 'id' or 'slug').
     * @param string|null $methodPrefix Optional controller method prefix for specialized routes (e.g., 'dashboard').
     * @return void
     */
    public static function defineDefaultRoutesOnly($only = [], $identifierAttribute = 'id', $methodPrefix = null)
    {
        $defaultRoutes = self::getDefaultRouteNames();

        foreach ($only as $name) {
            if (in_array($name, $defaultRoutes)) {
                self::defineRouteByName($name, $identifierAttribute, $methodPrefix);
            }
        }
    }
}

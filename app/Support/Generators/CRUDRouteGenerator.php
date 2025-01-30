<?php

namespace App\Support\Generators;

use Illuminate\Support\Facades\Route;

/**
 * Class CRUDRouteGenerator
 *
 * This class provides helper methods for defining CRUD-related routes with customizable middleware
 * for viewing and editing functionalities.
 */
class CRUDRouteGenerator
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
     * @param string|null $viewMiddleware Middleware for viewing actions.
     * @param string|null $editMiddleware Middleware for editing actions.
     * @return void
     */
    public static function defineRouteByName($name, $identifierAttribute = 'id', $viewMiddleware = null, $editMiddleware = null)
    {
        // Define routes based on the provided name and identifier attribute.
        switch ($name) {
            case 'index':
                Route::get('/', 'index')->name('index')->middleware($viewMiddleware);
                break;
            case 'trash':
                Route::get('/trash', 'trash')->name('trash')->middleware($viewMiddleware);
                break;
            case 'create':
                Route::get('/create', 'create')->name('create')->middleware($editMiddleware);
                break;
            case 'show':
                Route::get('/view/{record:' . $identifierAttribute . '}', 'show')->name('show')->middleware($viewMiddleware);
                break;
            case 'edit':
                Route::get('/edit/{record:' . $identifierAttribute . '}', 'edit')->name('edit')->middleware($editMiddleware);
                break;
            case 'store':
                Route::post('/store', 'store')->name('store')->middleware($editMiddleware);
                break;
            case 'update':
                Route::patch('/update/{record}', 'update')->name('update')->middleware($editMiddleware);
                break;
            case 'destroy':
                Route::delete('/destroy', 'destroy')->name('destroy')->middleware($editMiddleware);
                break;
            case 'restore':
                Route::patch('/restore', 'restore')->name('restore')->middleware($editMiddleware);
                break;
        }
    }

    /**
     * Define all default CRUD routes.
     *
     * @param string $identifierAttribute The attribute used for identifying records in the 'show' route (e.g., 'id' or 'slug').
     * @param string|null $viewMiddleware Middleware for viewing actions.
     * @param string|null $editMiddleware Middleware for editing actions.
     * @return void
     */
    public static function defineAllDefaultRoutes($identifierAttribute = 'id', $viewMiddleware = null, $editMiddleware = null)
    {
        $defaultRoutes = self::getDefaultRouteNames();

        foreach ($defaultRoutes as $route) {
            self::defineRouteByName($route, $identifierAttribute, $viewMiddleware, $editMiddleware);
        }
    }

    /**
     * Define default CRUD routes, excluding specified routes.
     *
     * @param array $excepts The routes to exclude from the definition.
     * @param string $identifierAttribute The attribute used for identifying records in the 'show' route (e.g., 'id' or 'slug').
     * @param string|null $viewMiddleware Middleware for viewing actions.
     * @param string|null $editMiddleware Middleware for editing actions.
     * @return void
     */
    public static function defineDefaultRoutesExcept($excepts = [], $identifierAttribute = 'id', $viewMiddleware = null, $editMiddleware = null)
    {
        $defaultRoutes = self::getDefaultRouteNames();

        // Filter out the excluded routes
        $routes = array_diff($defaultRoutes, $excepts);

        foreach ($routes as $route) {
            self::defineRouteByName($route, $identifierAttribute, $viewMiddleware, $editMiddleware);
        }
    }

    /**
     * Define only specific CRUD routes.
     *
     * @param array $only The routes to include in the definition.
     * @param string $identifierAttribute The attribute used for identifying records in the 'show' route (e.g., 'id' or 'slug').
     * @param string|null $viewMiddleware Middleware for viewing actions.
     * @param string|null $editMiddleware Middleware for editing actions.
     * @return void
     */
    public static function defineDefaultRoutesOnly($only = [], $identifierAttribute = 'id', $viewMiddleware = null, $editMiddleware = null)
    {
        $defaultRoutes = self::getDefaultRouteNames();

        foreach ($only as $name) {
            if (in_array($name, $defaultRoutes)) {
                self::defineRouteByName($name, $identifierAttribute, $viewMiddleware, $editMiddleware);
            }
        }
    }
}

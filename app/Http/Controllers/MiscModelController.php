<?php

namespace App\Http\Controllers;

use App\Support\Helpers\ModelHelper;
use App\Support\Helpers\QueryFilterHelper;
use Illuminate\Http\Request;

/**
 * Important: All defined models must have 'name' attribute!
 * Important: All defined models must implement 'TracksUsageCount' interface!
 */
class MiscModelController extends Controller
{
    const DEFAULT_PAGINATION_LIMIT = 50;

    /**
     * Display models list for specific department.
     */
    public function departmentModels(Request $request)
    {
        $department = $request->route('department');
        $models = $this->collectModelDefinitionsOfDepartment($department);

        return view('misc-models.department-models', compact('department', 'models'));
    }

    public function index(Request $request, $modelName)
    {
        // Find model and initialize it
        $model = $this->findModelByName($modelName);
        $this->addFullNamespaceToSpecificModelDefinition($model);

        // Get model records fitlered and paginated
        $records = $this->getModelRecordsFilteredAndPaginated($model, $request);

        return view('misc-models.index', compact('model', 'records'));
    }

    /*
    |--------------------------------------------------------------------------
    | Private helper functions
    |--------------------------------------------------------------------------
    */

    /**
     * Important: All defined models must have 'name' attribute!
     */
    private function collectAllModelDefinitions()
    {
        $models = collect([
            collect(['name' => 'Country', 'caption' => 'Countries', 'attributes' => ['name', 'code'], 'departments' => ['MAD']]),
            collect(['name' => 'Inn', 'caption' => 'Inns', 'attributes' => ['name'], 'departments' => ['MAD']]),
            collect(['name' => 'ManufacturerBlacklist', 'caption' => 'Manufacturer blacklists', 'attributes' => ['name'], 'departments' => ['MAD']]),
            collect(['name' => 'ManufacturerCategory', 'caption' => 'Manufacturer categories', 'attributes' => ['name'], 'departments' => ['MAD']]),
            collect(['name' => 'MarketingAuthorizationHolder', 'caption' => 'Marketing authorization holders', 'attributes' => ['name'], 'departments' => ['MAD']]),
            collect(['name' => 'PortfolioManager', 'caption' => 'Portfolio managers', 'attributes' => ['name'], 'departments' => ['MAD']]),
            collect(['name' => 'ProcessResponsiblePerson', 'caption' => 'Process responsible people', 'attributes' => ['name'], 'departments' => ['MAD']]),
            collect(['name' => 'ProductClass', 'caption' => 'Product classes', 'attributes' => ['name'], 'departments' => ['MAD']]),
            collect(['name' => 'ProductForm', 'caption' => 'Product forms', 'attributes' => ['name', 'parent_id'], 'departments' => ['MAD']]),
            collect(['name' => 'ProductSearchPriority', 'caption' => 'KVPP priorities', 'attributes' => ['name'], 'departments' => ['MAD']]),
            collect(['name' => 'ProductSearchStatus', 'caption' => 'KVPP statusses', 'attributes' => ['name'], 'departments' => ['MAD']]),
            collect(['name' => 'ProductShelfLife', 'caption' => 'Product shelf lives', 'attributes' => ['name'], 'departments' => ['MAD']]),
            collect(['name' => 'Zone', 'caption' => 'Zones', 'attributes' => ['name'], 'departments' => ['MAD']]),
        ]);

        return $models;
    }

    /**
     * Used only on department models page.
     */
    private function collectModelDefinitionsOfDepartment($department)
    {
        $models = $this->collectAllModelDefinitions()
            ->filter(function ($model) use ($department) {
                return in_array($department, $model['departments']);
            });

        $models = $this->addFullNamespaceToModelDefinitions($models);
        $models = $this->addRecordsCountToAllModelDefinitions($models);

        return $models;
    }

    /**
     * Add 'full_namespace' to each model definitions to avoid repetitions.
     *
     * Used only on department models page.
     */
    private function addFullNamespaceToModelDefinitions($models)
    {
        return $models->map(function ($model) {
            $this->addFullNamespaceToSpecificModelDefinition($model);
            return $model;
        });
    }

    /**
     * Add 'full_namespace' to model specific definition to avoid repetitions.
     *
     * Used almost on each routes.
     */
    private function addFullNamespaceToSpecificModelDefinition($model)
    {
        $model['full_namespace'] = ModelHelper::addFullNamespaceToModelBasename($model['name']);
    }

    /**
     * Add 'records_count' to model definitions.
     * Requires defined of 'full_namespace' attribute on models!
     *
     * Used only on department models page.
     */
    private function addRecordsCountToAllModelDefinitions($models)
    {
        return $models->map(function ($model) {
            return $this->addRecordsCountToSpecificModelDefinition($model);
        });
    }

    /**
     * Add 'records_count' to a single model definition.
     * Requires defined of 'full_namespace' attribute!
     *
     * Used only on department models page.
     */
    private function addRecordsCountToSpecificModelDefinition($model)
    {
        $fullNamespace = $model['full_namespace'];
        $model['record_count'] = $fullNamespace::count();

        return $model;
    }

    /**
     * Used on CRUD pages of specific model.
     */
    private function findModelByName($name)
    {
        return $this->collectAllModelDefinitions()
            ->where('name', $name)
            ->first();
    }

    /**
     * Used on index page of specific model.
     */
    private function getModelRecordsFilteredAndPaginated($model, $request)
    {
        // Eager load usage counts
        $query = $model['full_namespace']::withRelatedUsageCounts();

        // Filter query
        $filterConfig = [
            'whereEqual' => ['id', 'parent_id'],
        ];
        $filteredQuery = QueryFilterHelper::applyFilters($query, $request, $filterConfig);

        // Paginate query
        $records = $filteredQuery->orderBy('name', 'asc')
            ->orderBy('id', 'asc')
            ->paginate(self::DEFAULT_PAGINATION_LIMIT, ['*'], 'page', $request->page)
            ->appends($request->except(['page']));

        return $records;
    }

    /**
     * Retrieve all parent records for a given model, if model is parentable.
     * Requires defined of 'full_namespace' attribute.
     *
     * Used on CRUD pages of specific model.
     */
    private static function getAllParentRecordsOfModel($model)
    {
        $modelAttributes = $model['attributes'];
        $parents = null;

        if (in_array('parent_id', $modelAttributes)) {
            $parents = $model['full_namespace']::onlyParents();
        }

        return $parents;
    }
}

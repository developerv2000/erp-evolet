<?php

namespace App\Http\Controllers\global;

use App\Http\Controllers\Controller;
use App\Http\Requests\MiscModelStoreRequest;
use App\Http\Requests\MiscModelUpdateRequest;
use App\Support\Helpers\ModelHelper;
use App\Support\Helpers\QueryFilterHelper;
use Illuminate\Http\Request;

/**
 * Important: All defined models must have 'name' attribute!
 *
 * Important: All defined models must implement 'TracksUsageCount' interface!
 * Important: All defined models must use 'PreventsDeletionIfInUse' trait!
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

        return view('global.misc-models.department-models', compact('department', 'models'));
    }

    public function index(Request $request, $modelName)
    {
        // Find model and initialize it
        $model = $this->findModelByName($modelName);
        $this->addFullNamespaceToSpecificModelDefinition($model);

        // Get model records fitlered and paginated
        $records = $this->getModelRecordsFilteredAndPaginated($model, $request);

        // Get records for filtering
        $allRecords = $model['full_namespace']::all();
        $parentRecords = $this->getAllParentRecordsOfModel($model);

        return view('global.misc-models.index', compact('model', 'records', 'allRecords', 'parentRecords'));
    }

    public function create(Request $request, $modelName)
    {
        // Find model and initialize it
        $model = $this->findModelByName($modelName);
        $this->addFullNamespaceToSpecificModelDefinition($model);

        // Get all parent records, if model attributes contains 'parent_id'
        $parentRecords = $this->getAllParentRecordsOfModel($model);

        return view('global.misc-models.create', compact('model', 'parentRecords'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MiscModelStoreRequest $request, $modelName)
    {
        // Find model and initialize it
        $model = self::findModelByName($modelName);
        $this->addFullNamespaceToSpecificModelDefinition($model);

        $model['full_namespace']::create($request->all());

        return to_route('misc-models.index', $modelName);
    }

    public function edit(Request $request, $modelName, $id)
    {
        // Find model and initialize it
        $model = self::findModelByName($modelName);
        $this->addFullNamespaceToSpecificModelDefinition($model);

        // Find model record
        $record = $model['full_namespace']::Find($id);

        // Get all parent records, if model attributes contains 'parent_id'
        $parentRecords = $this->getAllParentRecordsOfModel($model);

        return view('global.misc-models.edit', compact('model', 'record', 'parentRecords'));
    }

    public function update(MiscModelUpdateRequest $request, $modelName, $id)
    {
        // Find model and initialize it
        $model = self::findModelByName($modelName);
        $this->addFullNamespaceToSpecificModelDefinition($model);

        // Find model record and update it
        $record = $model['full_namespace']::Find($id);
        $record->update($request->all());

        return redirect($request->input('previous_url'));
    }

    public function destroy(Request $request, $modelName)
    {
        // Find model and initialize it
        $model = $this->findModelByName($modelName);
        $this->addFullNamespaceToSpecificModelDefinition($model);

        // Delete selected records
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            $model['full_namespace']::find($id)->delete();
        }

        return redirect()->back();
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
        $model['records_count'] = $fullNamespace::count();

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
            'whereIn' => ['id', 'parent_id'],
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
    private function getAllParentRecordsOfModel($model)
    {
        $modelAttributes = $model['attributes'];
        $parents = null;

        if (in_array('parent_id', $modelAttributes)) {
            $parents = $model['full_namespace']::onlyParents()->get();
        }

        return $parents;
    }
}

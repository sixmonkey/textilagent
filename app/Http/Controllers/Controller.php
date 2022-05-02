<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Http\Requests\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * The model for this resource
     *
     * @var string
     */
    public string $model = User::class;

    /**
     * allowed sort parameters
     *
     * @var array
     */
    protected array $allowed_sorts = [];

    /**
     * allowed filters
     *
     * @var array
     */
    protected array $allowed_filters = [];

    /**
     * allowed includes
     *
     * @var array
     */
    protected array $allowed_includes = [];

    /**
     * allowed fields
     *
     * @var array
     */
    protected array $allowed_fields = [];

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', $this->model);

        if (method_exists($this->model, 'scopeSearch')) {
            $this->allowed_filters[] = AllowedFilter::scope('search');
        }

        $result = QueryBuilder::for($this->model)
            ->allowedSorts($this->allowed_sorts)
            ->allowedFilters($this->allowed_filters)
            ->allowedIncludes($this->allowed_includes);

        return
            JsonResource::collection(
                $request->has('page') ?
                    $result->jsonPaginate() :
                    $result->get()
            );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResource
     * @throws AuthorizationException
     */
    public function store(Request $request): JsonResource
    {
        $this->authorize('create', $this->model);

        $m = new $this->model();
        #dd($this->model::definedRelations()); // dump and die as array

        $new = $this->model::create($request->only($m->getFillable()));

        dump($this->model::definedRelations());

        return new JsonResource($new);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResource
     * @throws AuthorizationException
     */
    public function show(int $id): JsonResource
    {
        $result = QueryBuilder::for($this->model)
            ->allowedSorts($this->allowed_sorts)
            ->allowedFields($this->allowed_fields)
            ->allowedIncludes($this->allowed_includes)
            ->find($id);

        $this->authorize('view', $result);

        return new JsonResource(
            $result
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}

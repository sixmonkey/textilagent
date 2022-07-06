<?php

namespace App\Http\Controllers;

use App\Http\Requests\Request;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\Response;

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
     * allowed scopes
     *
     * @var array
     */
    protected array $allowed_filter_scopes = [];

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
     * equivalent of good old CakePHP beforeEach
     *
     * @param $method
     * @param $parameters
     * @return Response|JsonResource
     */
    public function callAction($method, $parameters): Response|JsonResource
    {
        $this->allowed_includes = collect($this->allowed_includes)
            ->map(function ($item) {
                return Str::camel($item);
            })
            ->toArray();

        return parent::callAction($method, $parameters);
    }

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

        foreach ($this->allowed_filter_scopes as $scope) {
            $this->allowed_filters[] = AllowedFilter::scope($scope);
        }

        $result = QueryBuilder::for($this->model)
            ->allowedSorts($this->allowed_sorts)
            ->allowedFilters($this->allowed_filters)
            ->allowedFields($this->allowed_fields)
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
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', $this->model);

        $entity = new $this->model();

        $new = call_user_func([$this->model, 'create'], $request->only($entity->getFillable()));

        $this->storeRelated($new, $request->collect());

        $result = QueryBuilder::for($this->model)
            ->allowedSorts($this->allowed_sorts)
            ->allowedFields($this->allowed_fields)
            ->allowedIncludes($this->allowed_includes)
            ->find($new->id);

        return (new JsonResource(
            $result
        ))
            ->toResponse($request)
            ->setStatusCode(201);
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
            ->findOrFail($id);

        $this->authorize('view', $result);

        return new JsonResource( // TODO: switch to explicit resources
            $result
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return object
     * @throws AuthorizationException
     */
    public function update(Request $request, int $id): object
    {
        $entity = call_user_func([$this->model, 'findOrFail'], $id);

        $this->authorize('update', $entity);

        $entity->update($request->only($entity->getFillable()));

        $this->storeRelated($entity, $request->collect());

        return new JsonResource(
            QueryBuilder::for($this->model)
                ->allowedSorts($this->allowed_sorts)
                ->allowedFields($this->allowed_fields)
                ->allowedIncludes($this->allowed_includes)
                ->find($entity->id)
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(int $id): JsonResponse
    {
        $entity = call_user_func([$this->model, 'findOrFail'], $id);

        $this->authorize('delete', $entity);

        if ($entity->delete()) {
            return new JsonResponse(['message' => 'Successfully deleted ' . ($entity->title ?? '#' . $entity->id) . '!']);
        }
        return new JsonResponse(['message' => 'Unknown error'], 500);
    }

    /**
     * Store related from request
     *
     * @param $for
     * @param $request
     * @return void
     */
    private function storeRelated($for, $request)
    {
        if (!method_exists($for, 'definedRelationships')) {
            return;
        }

        foreach (call_user_func([$for, 'definedRelationships']) as $relationship => $type) {
            $relationship_key = Str::snake($relationship);

            if ($request->has($relationship_key)) {
                $data = $request->get($relationship_key);

                if (!is_array($data)) {
                    continue;
                }
                switch ($type) {
                    case 'BelongsTo':
                        $this->storeBelongsTo($for, $relationship, $data);
                        break;
                    case 'HasMany':
                        $this->storeHasMany($for, $relationship, $data);
                        break;
                }
                $for->save();
            }
        }
    }

    /**
     * @param $for
     * @param $relationship
     * @param $data
     * @return void
     */
    private function storeBelongsTo($for, $relationship, $data)
    {
        $related = call_user_func([$for, $relationship])
            ->getRelated()
            ->where('id', $data['id'] ?? null) // TODO make sure id exists when set
            ->firstOr(function () use ($for, $relationship, $data) {
                return call_user_func([$for, $relationship])
                    ->getRelated()->create($data);
            });
        $this->storeRelated($related, collect($data));
        call_user_func([$for, $relationship])->associate($related);
    }

    /**
     * @param $for
     * @param $relationship
     * @param $data
     * @return void
     */
    private function storeHasMany($for, $relationship, $data)
    {
        $newItems = [];
        $related = call_user_func([$for, $relationship])->getRelated();
        collect($data)->each(function ($item) use ($for, $relationship, $related, &$newItems) {
            $newItem = call_user_func([$related, 'where'], ['id' => $item['id'] ?? null])
                ->firstOr(function () use ($for, $relationship, $item) {
                    return call_user_func([$for, $relationship])
                        ->getRelated()
                        ->create($item);
                });

            $newItem->fill($item);
            $newItems[] = $newItem;

            $this->storeRelated($newItem, collect($item));
        });
        call_user_func([$for, $relationship])->whereNotIn('id', collect($newItems)->pluck('id'))->delete();
        call_user_func([$for, $relationship])->saveMany($newItems);
    }
}

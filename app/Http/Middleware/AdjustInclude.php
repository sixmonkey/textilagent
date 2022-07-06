<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class AdjustInclude
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if ($includes = $request->get('include')) {
            $adjustedIncludes = new Collection();

            $query = $request->query->all();

            collect(explode(',', $includes))->each(function ($include) use (&$adjustedIncludes) {
                $adjustedIncludes->push(Str::camel($include));
            });

            $query['include'] = $adjustedIncludes->join(',');

            $request->query->replace($query);
        }

        return $next($request);
    }
}

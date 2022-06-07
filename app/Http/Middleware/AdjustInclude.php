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

            collect(explode(',', $includes))->each(function ($include) use (&$adjustedIncludes) {
                $adjustedIncludes->push(Str::camel($include));
            });

            $request->query->replace(['include' => $adjustedIncludes->join(',')]);
        }
        return $next($request);
    }
}

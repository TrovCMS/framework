<?php

namespace Trov\Concerns;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

trait CanModifyRoutes
{
    public function addToRoutes(array $routes, bool $bottom = false): void
    {
        $routeFile = File::get(base_path('routes/web.php'));
        $routesArray = collect(explode("\n", $routeFile))->toArray();

        if (Str::of($routeFile)->contains($routes[0])) {
            return;
        }

        $output = [];

        foreach ($routesArray as $k => $line) {
            $find = $bottom ? 'pages.show' : 'welcome';

            if (Str::of($line)->contains($find)) {
                $padding = Str::of($line)->before('Route');

                $toAdd = collect($routes)->transform(function ($route) use ($padding) { return $padding.$route; })->toArray();

                if ($bottom) {
                    array_splice($output, $k - 2, 0, array_merge([""], $toAdd));
                    array_push($output, $line);
                } else {
                    array_push($output, $line);
                    array_push($output, "");
                    array_splice($output, $k + 2, 0, $toAdd);
                }
            } else {
                array_push($output, $line);
            }
        }

        File::put(base_path('routes/web.php'), implode("\n", Arr::flatten($output)));
    }
}

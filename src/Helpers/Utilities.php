<?php

use Illuminate\Database\Eloquent\Model;

if (! function_exists('random_password')) {
    function random_password(): string
    {
        $random = str_shuffle('abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890!$%^&!$%^&');

        return substr($random, 0, 10);
    }
}

if (! function_exists('untrailing_slash_it')) {
    function untrailing_slash_it(string $string): string
    {
        return rtrim($string, '/\\');
    }
}

if (! function_exists('trailing_slash_it')) {
    function trailing_slash_it(string $string): string
    {
        if ($string != config('app.url')) {
            return untrailing_slash_it($string) . '/';
        }

        return $string;
    }
}

if (! function_exists('active_route')) {
    function active_route(string $route, $active = true, $default = false)
    {
        if (
            url()->current() == $route ||
            str(url()->current())->remove(config('app.url')) == untrailing_slash_it($route)) {
            return $active;
        }

        return $default;
    }
}

if (! function_exists('get_route_base')) {
    function get_route_base(string $route_name)
    {
        $routeItem = collect(app(Illuminate\Routing\Router::class)->getRoutes())->filter(function ($route) use ($route_name) {
            return $route->getName() === $route_name;
        })->first();

        if ($routeItem) {
            return $routeItem->uri;
        }

        return '/';
    }
}

if (! function_exists('is_front_page')) {
    function is_front_page(?Model $record)
    {
        if (isset($record['front_page']) && $record['front_page']) {
            return true;
        }

        return false;
    }
}

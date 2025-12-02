<?php

if (!function_exists('routeMatch')) {
    function routeMatch($haystack)
    {
        $needle = request()->route()->getName();

        return in_array($needle, $haystack);
    }
}

if (!function_exists('routeMatchExact')) {
    function routeMatchExact($routeName, $parameters = [])
    {
        $needle = request()->route()->getName();
        $routeMatch = true;

        if ($parameters) {
            foreach ($parameters as $parameterKey => $parameter) {
                if (request()->$parameterKey != $parameter) {
                    $routeMatch = false;
                }
            }
        }

        if ($needle != $routeName) {
            $routeMatch = false;
        }

        return $routeMatch;
    }
}

if (!function_exists('previous_route')) {
    /**
     * Generate a route name for the previous request.
     *
     * @return string|null
     */
    function previous_route()
    {
        try {
            $previousRequest = app('request')->create(app('url')->previous());
            $routeName = app('router')->getRoutes()->match($previousRequest)->getName();
        } catch (\Exception $exception) {
            return;
        }

        return $routeName;
    }
}

<?php

namespace Cachet;

use Cachet\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class Cachet
{
    /**
     * The user agent used by Cachet.
     *
     * @var string
     */
    const USER_AGENT = 'Cachet/1.0';

    /**
     * Cachet is being used to authenticate users.
     */
    public static bool $withAuthentication = false;

    /**
     * Get the default CSS variables.
     *
     * @return array<string, array<string,string>>
     */
    public static function cssVariables(): array
    {
        return [
            // Variable => [Light, Dark]
            'background' => ['#F9FAFB', '#18181B'],
            'text' => ['#3F3F46', '#D4D4D8'],
        ];
    }

    /**
     * Get the current user using `cachet.guard`.
     */
    public static function user(?Request $request = null)
    {
        $guard = config('cachet.guard');

        if (is_null($request)) {
            return call_user_func(app('auth')->userResolver(), $guard);
        }

        return $request->user($guard);
    }

    /**
     * Register the Cachet routes.
     */
    public static function routes(): PendingRouteRegistration
    {
        Route::aliasMiddleware('cachet.guest', RedirectIfAuthenticated::class);

        return new PendingRouteRegistration();
    }

    /**
     * Enable Cachet's authentication routes.
     */
    public static function withAuthentication(): static
    {
        static::$withAuthentication = true;

        return new static();
    }

    /**
     * Get the URI path prefix used by Cachet.
     */
    public static function path(): string
    {
        return config('cachet.path', '/status');
    }

    /**
     * Get the version of Cachet.
     */
    public static function version(): string
    {
        return trim(file_get_contents(__DIR__.'/../VERSION'));
    }
}

?php
namespace App\Http;

use App\Http\Middleware\AuthToken;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
class Kernel extends HttpKernel
{

protected $routeMiddleware = [
    // ... other middlewares
    'admin' => \App\Http\Middleware\CheckAdmin::class,
];

protected $middlewareGroups = [
    'api' => [
        \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],
];


}
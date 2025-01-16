<?php

use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
           ->withRouting(
               web: __DIR__ . '/../routes/web.php',
               commands: __DIR__ . '/../routes/console.php',
               health: '/up',
           )
           ->withMiddleware(function (Middleware $middleware) {
               $middleware->alias([
                   'role'               => \Spatie\Permission\Middleware\RoleMiddleware::class,
                   'permission'         => \Spatie\Permission\Middleware\PermissionMiddleware::class,
                   'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class
               ]);
               $middleware->redirectGuestsTo(function (Request $request) {
                   if ($request->is('api/*')) {
                       return null;
                   } else {
                       return route('login');
                   }
               });
           })
           ->withExceptions(function (Exceptions $exceptions) {
               //
           })
           ->create();

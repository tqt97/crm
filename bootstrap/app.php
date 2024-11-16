<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Exceptions\UnauthorizedException;

use App\Http\Middleware\ForceJsonRequestHeader;
use App\Http\Middleware\CrosMiddleware;
use App\Http\Middleware\UserAccessMiddleware;
use App\Http\Middleware\AppendRequestMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->validateCsrfTokens(
            except: ['stripe/*']
        );
        // Force json for all incoming requests
        $middleware->append(ForceJsonRequestHeader::class);
        $middleware->appendToGroup('api', [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful ::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
        $middleware->append(CrosMiddleware::class);
        // Role Admin and role Employee
        $middleware->alias([
            'user-access' => UserAccessMiddleware::class,
            'append-request' => AppendRequestMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Custom Rendering
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            return $request->expectsJson()
            ? response()->json([
                'success' => false,
                'message' => __('Unauthorized'),
            ], Response::HTTP_UNAUTHORIZED)
            : redirect()->guest(route('login'));
        });
        // Custom Rendering User does not have the right permissions
        $exceptions->render(function (UnauthorizedException $e, Request $request) {
            return $request->expectsJson()
            ? response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_FORBIDDEN)
            : abort(Response::HTTP_FORBIDDEN);
        });
        // Custom Rendering for NotFoundHttpException
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return sendError(config('erp.msg_not_found'), [], Response::HTTP_NOT_FOUND);
            }
        });
        // global exception
        $exceptions->report(function (Throwable $exception) {
            $log = [
                'message'           => $exception->getMessage(),
                'line'              => $exception->getLine(),
                'url'               => request()->getUri(),
                'method'            => request()->getMethod(),
                'request-params'    => request()->all(),
                'user'              => auth()->user()->name ?? '',
                'ip'                => request()->ip(),
            ];

            Log::error($log);

            return false; // prevent Laravel write log.
        });

        $exceptions->render(function (Throwable $e, Request $request) {
            return sendError(config('erp.msg_error'), [], Response::HTTP_BAD_REQUEST);
        });
    })
    ->create();

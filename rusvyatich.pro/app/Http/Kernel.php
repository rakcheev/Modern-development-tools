<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'AuthMine' => \App\Http\Middleware\AuthMine::class,
        'OnlyAuth' => \App\Http\Middleware\OnlyAuth::class,
        'Entitle' => \App\Http\Middleware\Entitle::class,
        'Purchase' => \App\Http\Middleware\Purchase::class, 
        'ViewListOrders' => \App\Http\Middleware\ViewListOrders::class, 
        'ViewOrderCartConstruct' => \App\Http\Middleware\ViewOrderCartConstruct::class,
        'ViewOrderIndividual' => \App\Http\Middleware\ViewOrderIndividual::class,
        'AdminAndMainMasterPost' => \App\Http\Middleware\AdminAndMainMasterPost::class,
        'AdminMainOperatorMainMasterPost' => \App\Http\Middleware\AdminMainOperatorMainMasterPost::class,
        'AdminOperatorMainMasterPost' => \App\Http\Middleware\AdminOperatorMainMasterPost::class,
        'MasterAndMainCustomerPost' => \App\Http\Middleware\MasterAndMainCustomerPost::class,
        'AdminAndMainMaster' => \App\Http\Middleware\AdminAndMainMaster::class,
        'AdminAndMainOperator' => \App\Http\Middleware\AdminAndMainOperator::class,
        'AdminOperatorMainMaster' => \App\Http\Middleware\AdminOperatorMainMaster::class,
        'ChangeStatusOrderAccess' => \App\Http\Middleware\ChangeStatusOrderAccess::class,
        'ChangeMasterAccess' => \App\Http\Middleware\ChangeMasterAccess::class,
        'ChangeSumOrderAccess' => \App\Http\Middleware\ChangeSumOrderAccess::class,
        'KnifeProperties' => \App\Http\Middleware\KnifeProperties::class,
        'OnlyAdmin' => \App\Http\Middleware\OnlyAdmin::class,
        'OnlyAdminPost' => \App\Http\Middleware\OnlyAdminPost::class,
        'OnlyCustomer' => \App\Http\Middleware\OnlyCustomer::class,
        'ForAuthorize' => \App\Http\Middleware\ForAuthorize::class,
        'WorkersRedirect' => \App\Http\Middleware\WorkersRedirect::class,
        'WorkersPageAccess' => \App\Http\Middleware\WorkersPageAccess::class
    ];
}
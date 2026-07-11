protected $middlewareGroups = [
    'web' => [
        // ...
        \App\Http\Middleware\LocaleMiddleware::class,
    ],
];
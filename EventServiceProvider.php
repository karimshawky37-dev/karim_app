protected $listen = [
    PartRestocked::class => [
        UpdateWaitingDevices::class,
    ],
];
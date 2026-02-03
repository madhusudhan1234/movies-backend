<?php

return [
    \App\Repositories\User\UserRepository::class => \App\Repositories\User\UserEloquentRepository::class,
    \App\Repositories\Movie\MovieRepository::class => \App\Repositories\Movie\MovieEloquentRepository::class,
];

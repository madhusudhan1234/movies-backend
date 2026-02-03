<?php

namespace App\Enums;

enum MovieCreditsRole: string
{
    case DIRECTOR = 'Director';
    case ACTOR = 'Actor';
    case WRITER = 'Writer';
    case PRODUCER = 'Producer';
}

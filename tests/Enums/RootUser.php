<?php

declare(strict_types=1);

namespace Tests\Enums;

enum RootUser: string
{
    case NAME = 'Lukasz Lupa';
    case EMAIL = 'lukasz.lupa@twirelab.com';
    case PASS = 'Th1sIsMyP@ss';
}

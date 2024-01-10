<?php

declare(strict_types=1);

namespace Lion\Mailer;

enum Priority: int
{
    case LOWEST = 5;
    case LOW = 4;
    case NORMAL = 3;
    case HIGH = 2;
    case HIGHEST = 1;
}

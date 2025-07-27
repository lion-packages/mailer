<?php

declare(strict_types=1);

namespace Lion\Mailer;

/**
 * Define priority levels.
 */
enum Priority: int
{
    /**
     * [Lowest priority level].
     */
    case LOWEST = 5;

    /**
     * [Low priority level].
     */
    case LOW = 4;

    /**
     * [Normal priority level].
     */
    case NORMAL = 3;

    /**
     * [High priority level].
     */
    case HIGH = 2;

    /**
     * [Highest priority level].
     */
    case HIGHEST = 1;
}

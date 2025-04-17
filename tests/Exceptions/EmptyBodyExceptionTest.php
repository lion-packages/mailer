<?php

declare(strict_types=1);

/*
 * chore: apply PSR-12 style fixes from StyleCI
 */

namespace Tests\Exceptions;

use Lion\Mailer\Exceptions\EmptyBodyException;
use Lion\Test\Test;
use PHPUnit\Framework\Attributes\Test as Testing;

class EmptyBodyExceptionTest extends Test
{
    #[Testing]
    public function make(): void
    {
        $this->expectException(EmptyBodyException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage(
            'message body has not been specified. Use the `body` method to specify the message body'
        );

        throw EmptyBodyException::make();
    }
}

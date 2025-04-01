<?php

declare(strict_types=1);

namespace Tests\Exceptions;

use Lion\Mailer\Exceptions\InvalidFromAddressException;
use Lion\Test\Test;
use PHPUnit\Framework\Attributes\Test as Testing;

class InvalidFromAddressExceptionTest extends Test
{
    #[Testing]
    public function emptyFromAddress(): void
    {
        $this->expectException(InvalidFromAddressException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage(
            'No from address has been provided. Use the `from`  method to specify sender address information'
        );

        throw InvalidFromAddressException::emptyFromAddress();
    }
}

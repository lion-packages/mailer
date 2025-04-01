<?php

declare(strict_types=1);

namespace Tests\Exceptions;

use Lion\Mailer\Exceptions\InvalidRecipientAddressException;
use Lion\Test\Test;
use PHPUnit\Framework\Attributes\Test as Testing;

class InvalidRecipientAddressExceptionTest extends Test
{
    #[Testing]
    public function emptyRecipientsList(): void
    {
        $this->expectException(InvalidRecipientAddressException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage(
            'no recepients has been provided. Use `addAddress` method once or multiple times to specify as many ' .
            'recipients information as needed'
        );

        throw InvalidRecipientAddressException::emptyRecipientsList();
    }
}

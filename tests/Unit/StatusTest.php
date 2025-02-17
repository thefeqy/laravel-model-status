<?php

namespace Thefeqy\ModelStatus\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Thefeqy\ModelStatus\Tests\TestCase;
use Thefeqy\ModelStatus\Status;

class StatusTest extends TestCase
{
    #[Test]
    public function it_can_return_active_status()
    {
        $this->assertEquals(config('model-status.default_value', 'active'), Status::active());
    }

    #[Test]
    public function it_can_return_inactive_status()
    {
        $this->assertEquals(config('model-status.inactive_value', 'inactive'), Status::inactive());
    }

    #[Test]
    public function it_can_check_if_status_is_active()
    {
        $status = new Status(Status::active());
        $this->assertTrue($status->isActive());
    }

    #[Test]
    public function it_can_check_if_status_is_inactive()
    {
        $status = new Status(Status::inactive());
        $this->assertTrue($status->isInactive());
    }

    #[Test]
    public function it_throws_an_error_for_invalid_status()
    {
        $this->expectException(\UnexpectedValueException::class);
        new Status('invalid_status');
    }
}

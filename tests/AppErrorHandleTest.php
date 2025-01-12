<?php

declare(strict_types=1);

namespace Waglpz\Webapp\Tests;

use FastRoute\Dispatcher;
use Interop\Http\EmitterInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Waglpz\Webapp\App;
use Waglpz\Webapp\ExceptionHandler;
use Waglpz\Webapp\ExceptionHandlerInvokable;

final class AppErrorHandleTest extends TestCase
{
    /** @test */
    public function convertToException(): void
    {
        $dispatcher = $this->createMock(Dispatcher::class);
        $dispatcher->expects(self::never())->method('dispatch');
        $emitter = $this->createMock(EmitterInterface::class);

        $exceptionHandler = $this->createMock(ExceptionHandlerInvokable::class);

        $this->expectException(\Error::class);
        $this->expectExceptionMessage('Test Error');

        (new App($dispatcher, $emitter, null, $exceptionHandler));
        \trigger_error('Test Error');
    }

    /** @test */
    public function exceptionHandlerEmmitErrorResponse(): void
    {
        $exceptionHandler = new ExceptionHandler();

        $exception = $this->createMock(\Throwable::class);
        $emitter   = $this->createMock(EmitterInterface::class);
        $emitter->expects(self::once())->method('emit')->with(self::isInstanceOf(ResponseInterface::class));
        $exceptionHandler($exception, $emitter);
        $exceptionHandler($exception);
    }
}

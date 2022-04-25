<?php

namespace Myerscode\Acorn\Testing\Interactions;

use Myerscode\Acorn\Foundation\Queue\SynchronousQueue;
use Myerscode\Acorn\Framework\Events\Dispatcher;
use Myerscode\Acorn\Framework\Queue\QueueInterface;

trait InteractsWithDispatcher
{
    protected ?Dispatcher $dispatcher = null;

    /**
     * Get current Dispatcher in test
     */
    public function dispatcher(QueueInterface $queue = new SynchronousQueue()): Dispatcher
    {
        if (!isset($this->dispatcher)) {
            $this->refreshDispatcher($queue);
        }

        return $this->dispatcher;
    }

    public function newDispatcher(QueueInterface $queue = new SynchronousQueue()): Dispatcher
    {
        return new Dispatcher($queue);
    }

    public function refreshDispatcher(QueueInterface $queue = new SynchronousQueue()): Dispatcher
    {
        $this->dispatcher = $this->newDispatcher($queue);

        return $this->dispatcher;
    }
}

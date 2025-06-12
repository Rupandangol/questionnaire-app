<?php

namespace App\Listeners;

use App\Events\BlogDeletedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogDeletedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BlogDeletedEvent $event): void
    {
        Log::info("Blog Deleted. Blog id=>".$event->blog->id." Blog Title=>". $event->blog->id);
    }
}

<?php

namespace App\Modules\AbstractModule;

class EventManager
{
    /**
     * @param array $events
     */
    public static function event(array $events)
    {
        foreach ($events as $event) {
            event($event);
        }
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Thread;

class LockedThreadsController extends Controller
{
    public function store(Thread $thread)
    {
       $thread->locked();
    }

    public function destroy(Thread $thread)
    {
        $thread->unlocked();
    }
}

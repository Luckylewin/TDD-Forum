<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use Illuminate\Http\Request;


class ThreadsController extends Controller
{
    /**
     * ThreadsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth')->only('store');
    }

    public function index()
    {
        $threads = Thread::query()->latest()->get();

        return view('threads.index', compact('threads'));
    }

    public function store(Request $request)
    {
        $thread = Thread::create([
            'user_id' => auth()->id(),
            'title'   => request('title'),
            'body'   => request('body'),
        ]);

        return redirect($thread->path());
    }

    public function show(Thread $thread)
    {
        return view('threads.show', compact('thread'));
    }


}
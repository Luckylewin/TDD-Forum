<?php

namespace App\Http\Controllers;

use App\Filters\ThreadsFilters;
use App\Models\Channel;
use App\Models\Thread;
use Illuminate\Http\Request;

class ThreadsController extends Controller
{
    /**
     * ThreadsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Channel $channel, ThreadsFilters $filters)
    {
        $threads = $this->getThreads($channel, $filters);

        if (request()->wantsJson()) {
            return $threads;
        }

        return view('threads.index', compact('threads'));
    }

    public function create()
    {
        return view('threads.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'channel_id' => 'required|exists:channels,id',
            'title' => 'required',
            'body' => 'required',
        ]);

        $thread = Thread::create([
            'user_id' => auth()->id(),
            'channel_id' => request('channel_id'),
            'title' => request('title'),
            'body' => request('body'),
        ]);

        return redirect($thread->path())
            ->with('flash','话题已经发布！');
    }

    public function destroy($channel, Thread $thread)
    {
        $this->authorize('update',$thread);

        $thread->delete();

        if (\request()->wantsJson()) {
            return response([], 204);
        }

        return redirect('/threads')
                ->with('flash','话题已删除');
    }

    public function show($channelId,Thread $thread)
    {
        return view('threads.show', [
            'thread' => $thread,
            'replies' => $thread->replies()->paginate(10)
        ]);
    }

    protected function getThreads(Channel $channel, ThreadsFilters $filters)
    {
        $threads = Thread::query()->latest()->filter($filters);

        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

        $threads = $threads->get();

        return $threads;
    }
}

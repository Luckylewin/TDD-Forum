<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use App\Models\Thread;
use Gate;

class RepliesController extends Controller
{

    /**
     * RepliesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }


    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

    public function store($channelId, Thread $thread)
    {
        if (Gate::denies('create', new Reply())) {
            return response(
                'Your are posting too frequently,Please take a break', 422
            );
        }
        try {
            $this->authorize('create', new Reply);

            $this->validate(request(), ['body' => 'required|spamfree']);

            $reply = $thread->addReply([
                'body'    => request('body'),
                'user_id' => auth()->id()
            ]);
        } catch (\Exception $e) {
            return response(
                'Sorry,your reply could not be saved at this time', 422
            );
        }

        return $reply->load('owner');
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->wantsJson()) {
            return response(['status' => 'Reply deleted']);
        }

        return back()->with('flash', '评论已删除');
    }

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        try {
            $this->validate(request(), ['body' => 'required|spamfree']);

            $reply->update(request(['body']));
        } catch (\Exception $e) {
            return response(
                'Sorry,your reply could not be saved at this time', 422
            );
        }
    }

}

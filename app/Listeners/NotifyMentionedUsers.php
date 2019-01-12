<?php

namespace App\Listeners;

use App\Events\ThreadReceivedNewReply;
use App\Models\Reply;
use App\Notifications\YouWereMentioned;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * 监听器：通知被提及的人
 * Class NotifyMentionedUsers
 * @package App\Listeners
 */
class NotifyMentionedUsers
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ThreadReceivedNewReply  $event
     * @return void
     */
    public function handle(ThreadReceivedNewReply $event)
    {
        /**
         * @var $reply Reply
         * @var $user User
         */
        $reply = $event->reply;

        User::whereIn('name', $reply->mentionedUsers())
            ->get()
            ->each(function ($user) use ($reply) {
                /**
                 * @var $user User
                 */
                $user->notify(new YouWereMentioned($reply));
            });
    }
}

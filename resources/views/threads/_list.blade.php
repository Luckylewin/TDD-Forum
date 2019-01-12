@forelse ($threads as $thread)
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="level">
                <div class="flex">
                    <h4>
                        <a href="{{ $thread->path() }}">
                            @if(auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                                <strong>
                                    {{ $thread->title }}
                                </strong>
                            @else
                                {{ $thread->title }}
                            @endif
                        </a>
                    </h4>

                    <h5>
                        Posted By:<a href="{{ route('profile',$thread->creator) }}">{{ $thread->creator->name }}</a>
                    </h5>
                </div>



                <a href="{{ $thread->path() }}">
                    {{ $thread->replies_count }} {{ str_plural('reply', $thread->replies_count) }}
                </a>
            </div>
        </div>

        <div class="panel-body">{{ $thread->body }}</div>

    </div>
    <hr>
@empty
    <div class="well text-center">
        <p><p>该频道没有话题~</p></p>
    </div>
@endforelse
<div id="reply-{{ $reply->id }}" class="panel panel-default">
    <div class="panel-heading">
        <div class="level">
            <h5 class="flex">
                <a href="{{ route('profile', $reply->owner->name) }}">{{ $reply->owner->name }}</a>
                回复于
                {{ $reply->created_at->diffForHumans() }}
            </h5>

            <div>
                 @if($reply->isFavorited() === false)
                    <form action="/replies/{{ $reply->id }}/favorites" method="post">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-default" title="favorite">
                        {{ $reply->favorites_count }}    ❤
                        </button>
                    </form>
                 @else
                        <button type="submit" class="btn btn-default" title="favorite">
                            {{ $reply->favorites_count }}  <font color="red">❤</font>
                        </button>
                 @endif
            </div>
        </div>
    </div>

    <div class="panel-body">
        {{ $reply->body }}
    </div>

    @can('update', $reply)
        <div class="panel-footer text-right">
            <form action="/replies/{{ $reply->id }}" method="post">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <button type="submit" class="btn btn-danger btn-xs">删除</button>
            </form>
        </div>
    @endcan
</div>
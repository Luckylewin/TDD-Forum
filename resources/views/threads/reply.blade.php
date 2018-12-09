<reply :attributes="{{ $reply }}" inline-template v-cloak>
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
            <div v-if="editing">
                <div class="form-group">
                    <textarea title="edit" class="form-control" v-model="body"></textarea>
                </div>

                <div class="text-right">
                    <button class="btn btn-primary btn-xs" @click="update">提交</button>
                    <button class="btn btn-link btn-xs" @click="editing = false">取消</button>
                </div>

            </div>

            <div v-else="body" v-text="body">
                {{ $reply->body }}
            </div>
        </div>

        @can('update', $reply)
            <div class="panel-footer level">

                <button class="btn btn-xs mr-1" @click="editing = true">编辑</button>

                <form action="/replies/{{ $reply->id }}" method="post">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button type="submit" class="btn btn-danger btn-xs">删除</button>
                </form>
            </div>
        @endcan
    </div>
</reply>
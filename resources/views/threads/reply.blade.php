<reply :attributes="{{ $reply }}" inline-template v-cloak>
    <div id="reply-{{ $reply->id }}" class="panel panel-default">
        <div class="panel-heading">
            <div class="level">
                <h5 class="flex">
                    <a href="{{ route('profile', $reply->owner->name) }}">{{ $reply->owner->name }}</a>
                    回复于
                    {{ $reply->created_at->diffForHumans() }}
                </h5>

                @if(auth()->check())
                <div>
                    <favorite :reply="{{ $reply }}"></favorite>
                </div>
                @endif

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
                <button class="btn btn-danger btn-xs mr-1" @click="destroy">删除</button>

            </div>
        @endcan
    </div>
</reply>

{{--编辑区域--}}
<div class="panel panel-default" v-if="editing">
    <div class="panel-heading">
        <div class="level">
            <input type="text" value="{{ $thread->title }}" class="form-control" v-model="form.title">
        </div>
    </div>

    <div class="panel-body">
        <div class="form-group">
            <textarea class="form-control" rows="10" v-model="form.body">{{ $thread->body }}</textarea>
        </div>
    </div>

    <div class="panel-footer">
        <div class="level">

            <button class="btn btn-primary btn-sm level-item" @click="update"><i class="glyphicon glyphicon-ok"></i> Update</button>
            <button class="btn btn-sm level-item" @click="resetForm"><i class="glyphicon glyphicon-remove"></i> Cancel</button>

            @can('update',$thread)
                <form action="{{ $thread->path() }}" method="POST" class="ml-a">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}

                    <button type="submit" class="btn btn-link">Delete Thread</button>
                </form>
            @endcan
        </div>
    </div>
</div>

{{--详情区域--}}
<div class="panel panel-default">
    <div class="panel-heading">

        <div class="level">
            <img src="/storage/{{ $thread->creator->avatar_path }}" alt="{{ $thread->creator->name }}" width="25" height="25" class="mr-1">

            <span class="flex">
              <a href="{{ route('profile',$thread->creator) }}">{{ $thread->creator->name }}</a> posted:
                {{ $thread->title }}
            </span>

            @can('update', $thread)
                <form action="{{ $thread->path() }}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button type="submit" class="btn btn-link" >删除</button>
                </form>
            @endcan
        </div>
    </div>

    <div class="panel-body" v-text="body">
        {{ $thread->body }}
    </div>

    <div class="panel-footer" v-if="authorize('owns',thread)">
        <button class="btn btn-sm btn-default " @click="editing = true" >
            <i class="glyphicon glyphicon-edit"> Edit</i>
        </button>
    </div>

</div>
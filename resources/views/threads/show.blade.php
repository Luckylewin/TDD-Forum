@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="/css/vendor/jquery.atwho.css">
@endsection

@section('content')
    <thread-view :thread="{{ $thread }}" inline-template>
        <div class="container">
            {{--详情区域--}}
            <div class="row">
                <div class="col-md-8">

                    {{--话题详情区域--}}
                    @include('threads._topic')

                    {{--回复区域--}}
                    <replies @removed="repliesCount--" @added="repliesCount++"></replies>

                </div>
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel panel-body">
                            <p>
                                <a href="#">{{ $thread->creator->name }}</a> 发布于 {{ $thread->created_at->diffForHumans() }},
                                当前共有 <span v-text="repliesCount"></span> 个回复
                            </p>
                            <p>
                                <Subscribe-button :active="{{ json_encode($thread->isSubscribedTo) }}"></Subscribe-button>
                                <button :class="locked ? 'btn btn-info' : 'btn btn-default'" v-if="authorize('isAdmin')" @click="toggleLock" v-text="locked ? '解除锁定' : '锁定话题'"></button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>
@endsection
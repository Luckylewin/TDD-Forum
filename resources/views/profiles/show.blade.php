@extends('layouts.app')

@section('content')
       <div class="container">
           <div class="row">
               <div class="col-md-offset-2">
                   <div class="page-header">
                       <h1>
                           {{ $profileUser->name }}
                           <small>注册于 {{ $profileUser->created_at->diffForHumans() }}</small>
                       </h1>
                   </div>

                       @forelse($threads as $thread):
                       <div class="panel panel-default">
                           <div class="panel-heading">
                               <div class="level">
                                   <div class="flex">
                                       <a href="{{ route('profile', $thread->creator->name) }}">{{ $thread->creator->name }}</a> 发表于

                                       <a href="/threads/{{$thread->channel->name}}">{{ $thread->channel->name }}</a>

                                       / {{ $thread->title }}
                                   </div>

                                   <span>{{ $thread->created_at->diffForHumans() }}</span>
                               </div>
                           </div>
                           <div class="panel-body">
                               {{ $thread->body }}
                           </div>
                       </div>
                       @empty
                           <div class="well well-lg text-center">
                               该用户没有发布过帖子
                           </div>
                       @endforelse

                   {{ $threads->links() }}
               </div>
           </div>
       </div>

@endsection
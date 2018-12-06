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

                        @forelse($activities as $date => $activity)
                           <h3 class="page-header">{{ $date }}</h3>

                           @foreach($activity as $record)
                               @include("profiles.activities.{$record->type}", ['activity' => $record])
                           @endforeach
                        @empty
                           <div class="well well-lg text-center">
                               该用户没有动态
                           </div>
                        @endforelse

               </div>
           </div>
       </div>

@endsection
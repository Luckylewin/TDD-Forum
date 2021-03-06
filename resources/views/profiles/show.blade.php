@extends('layouts.app')

@section('content')
       <div class="container">
           <div class="row">
               <div class="col-md-offset-2">
                   <div class="page-header">
                       <h1>
                           {{ $profileUser->name }}
                       </h1>

                       <avatar-form :user="{{ $profileUser }}"></avatar-form>

                   </div>

                        @forelse($activities as $date => $activity)
                           <h3 class="page-header">{{ $date }}</h3>

                           @foreach($activity as $record)
                               @if(view()->exists("profiles.activities.{$record->type}"))
                                    @include("profiles.activities.{$record->type}", ['activity' => $record])
                               @endif
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
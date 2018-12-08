@component('profiles.activities.activity')
    @slot('heading')
        {{ $profileUser->name }} 进行了
        <a href="{{ $activity->subject->favorited->path() }}">
            点赞
        </a>
    @endslot
    @slot('body')
        {{ $activity->subject->favorited->body }}
    @endslot
@endcomponent
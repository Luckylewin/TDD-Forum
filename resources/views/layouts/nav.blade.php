<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- 图标 -->
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- 左侧菜单 -->
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-hidden="true"
                       aria-expanded="false">Browse <span class="caret"></span> </a>

                    <ul class="dropdown-menu">
                        <li><a href="/threads">ALL Threads</a> </li>
                        @if (auth()->check())
                        <li><a href="/threads?by={{ auth()->user()->name }}">My Threads</a> </li>
                        @endif
                        <li><a href="/threads?popularity=1">最热的帖子</a></li>
                        <li><a href="/threads?unanswered=1">零回复</a></li>
                    </ul>
                </li>
                <li><a href="{{ url('/threads/create') }}">New Threads</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-left">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        Channels <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        @foreach($channels as $channel)
                        <li><a href="/threads/{{ $channel->slug }}">{{ $channel->name }}</a> </li>
                        @endforeach
                    </ul>
                </li>
            </ul>

            <!-- 右侧菜单 -->
            <ul class="nav navbar-nav navbar-right">
                <!-- 登录入口 -->
                @if (Auth::guest())
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Register</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="/profiles/{{ Auth::user()->name }}">我的资料</a>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    退出登录
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
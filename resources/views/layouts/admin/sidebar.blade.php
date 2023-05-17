<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <div class="header-left">
                <a href="/" class="logo d-flex justify-content-center">
                    <div>
                        <img width="100%" src="{{ asset('user/img/dash/Softlogo.png') }}" alt="">
                    </div>
                </a>
            </div>
            <ul class="sidebar-ul">
                @foreach (config('conversations') as $conversation)
                    <li class="{{-- Request::is('admin')?'active':'' --}}">
                        <a href="{{ route('admin.index') }}"><img src="{{ asset('user/img/sidebar/icon-1.png') }}" alt="icon"><span>{{ $conversation->name }}</span></a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

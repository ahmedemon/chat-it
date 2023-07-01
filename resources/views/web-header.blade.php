<ul class="navbar-nav ms-auto">
    <li class="nav-item dropdown">
        <a id="navbarDropdown" class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
            <i class="fas fa-ellipsis-v" style="font-size: 20px;"></i>
        </a>

        <div class="dropdown-menu dropdown-menu-end py-0 rounded-0" style="width: 100% !important;" aria-labelledby="navbarDropdown">
            <a class="btn btn-block text-start ps-3 w-100 rounded-0" href="{{ route('web') }}?{{ $admin_id }}?&site={{ $_GET['site'] ?? '' }}&name={{ Auth::user()->name }}&email={{ Auth::user()->email }}&phone={{ Auth::user()->phone }}">
                {{ __('Inbox') }}
            </a>
            <a class="btn btn-block text-start ps-3 w-100 rounded-0" href="{{ route('group-web') }}?{{ $admin_id }}?&site={{ $_GET['site'] ?? '' }}&name={{ Auth::user()->name }}&email={{ Auth::user()->email }}&phone={{ Auth::user()->phone }}">
                {{ __('Group Chat') }}
            </a>
            <a class="btn btn-block text-start ps-3 w-100 rounded-0" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </li>
</ul>

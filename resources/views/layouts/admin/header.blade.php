<div class="border-bottom d-flex align-items-center justify-content-between" style="height: 6vh;">
    <div class="d-flex align-items-center">
        <img src="{{ Auth::guard('admin')->user()->profile_logo ? asset('storage/admin/' . Auth::guard('admin')->user()->profile_logo) : asset('assets/soft-favicon-logo.png') }}" height="35" width="35" class="ms-2 rounded-circle border" alt="">
        <span class="ms-3">{{ Auth::guard('admin')->user()->name }}</span>
    </div>
    <div class="me-4">
        <ul class="navbar-nav ms-auto flex-row">
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="btn btn-sm btn-outline-dark px-1 ms-1" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    <i class="fas fa-chevron-circle-down" style="font-size: 2.5vh;"></i>
                </a>

                <div class="dropdown-menu dropdown-menu-end py-0 rounded-0" aria-labelledby="navbarDropdown">
                    <a class="btn btn-sm btn-block w-100 rounded-0" href="{{ route('admin.index') }}">
                        {{ __('Home') }}
                    </a>
                    <a class="btn btn-sm btn-block w-100 rounded-0" href="{{ route('admin.profile.index') }}">
                        {{ __('Profile') }}
                    </a>
                    <a class="btn btn-sm btn-block w-100 rounded-0" href="{{ route('admin.logout') }}?panel={{ Auth::guard('admin')->user()->id }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
            <li class="nav-item dropdown">
                <button class="btn btn-sm d-lg-none btn-outline-dark px-1 ms-1 toggle"><i class="fas fa-sliders-h" style="font-size: 2.5vh;"></i></button>
            </li>
        </ul>
    </div>
</div>

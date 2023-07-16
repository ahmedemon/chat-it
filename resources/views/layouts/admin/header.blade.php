<div class="border-bottom d-flex align-items-center justify-content-between" style="height: 6vh;">
    <div class="d-flex align-items-center">
        <img src="{{ Auth::guard('admin')->user()->profile_logo ? asset('storage/admin/' . Auth::guard('admin')->user()->profile_logo) : asset('assets/soft-favicon-logo.png') }}" height="35" width="35" class="ms-2 rounded-circle border" alt="">
        <span class="ms-3">{{ Auth::guard('admin')->user()->name }}</span>
    </div>
    <div class="me-4">
        <ul class="navbar-nav ms-auto flex-row align-items-center">
            <li class="nav-item">
                @if (Auth::guard('admin')->user()->sound == 1)
                    <a class="btn btn-sm btn-outline-dark px-1 ms-1" href="{{ route('admin.sound.on.off') }}"><i class="fas fa-volume-up" style="font-size: 2.5vh;"></i></a>
                @else
                    <a class="btn btn-sm btn-outline-dark px-1 ms-1" href="{{ route('admin.sound.on.off') }}"><i class="fas fa-volume-mute" style="font-size: 2.5vh;"></i></a>
                @endif
            </li>
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="btn btn-sm btn-outline-dark px-1 ms-1" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    <i class="fas fa-chevron-circle-down" style="font-size: 2.5vh;"></i>
                </a>

                <div class="dropdown-menu dropdown-menu-end py-0 rounded-0" aria-labelledby="navbarDropdown">
                    <a class="text-start ps-2 btn btn-sm btn-block w-100 rounded-0" href="{{ route('admin.index') }}">
                        {{ __('Home') }}
                    </a>
                    <a class="text-start ps-2 btn btn-sm btn-block w-100 rounded-0" href="{{ route('admin.profile.index') }}">
                        {{ __('Profile') }}
                    </a>
                    @php
                        $conversation = \App\Models\Conversation::where('admin_id', Auth::guard('admin')->user()->id)
                            ->where('is_group', 1)
                            ->first();
                    @endphp
                    <a class="text-start ps-2 btn btn-sm btn-block w-100 rounded-0" href="{{ route('admin.view.group', $conversation->id) }}">
                        {{ __('Group') }}
                    </a>
                    @if (Auth::guard('admin')->user()->main_account == 1)
                        <a class="text-start ps-2 btn btn-sm btn-block w-100 rounded-0" href="{{ route('admin.panel.index') }}">
                            {{ __('Dashboard') }}
                        </a>
                    @endif
                    <a class="text-start ps-2 btn btn-sm btn-block w-100 rounded-0" href="{{ route('admin.logout') }}?panel={{ Auth::guard('admin')->user()->id }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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
@push('js')
    <script>
        $(".toggle").on('click', function() {
            $("#sidebar").toggleClass('d-none');
        });

        $(".toggle-home").on('click', function() {
            $("#conversation_id").val('');
            $("#grettings-page").removeClass('d-none');
            $("#header-right").addClass('d-none');
            $(".btn-outline-dark").removeClass('active');
        });
    </script>
@endpush

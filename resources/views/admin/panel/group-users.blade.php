@extends('layouts.admin.app', ['pageTitle' => $pageTitle])
@section('content')
    <div class="row justify-content-center mx-auto align-items-center" id="main_container">
        <div class="col-12">
            <div class="row bg-white">
                <div class="col-12 col-md-12 px-0 border-end bg-white sticky-sm-top">
                    @include('layouts.admin.header')
                    <div class="" style="height: 94vh; overflow-y: scroll;">
                        <div class="row mx-auto justify-content-center">
                            @include('admin.panel.sidebar')
                            <div class="col-md-9">
                                <div class="card mt-3">
                                    <div class="card-body table-responsive">
                                        <table class="table table-sm table-borderless text-center table-striped mb-0">
                                            <thead>
                                                <th>SL</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Password</th>
                                                <th>Action</th>
                                            </thead>
                                            <tbody>
                                                @foreach ($conversations as $conversation)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $conversation->user->name ?? 'N/A' }}</td>
                                                        <td>{{ $conversation->user->email ?? 'N/A' }}</td>
                                                        <td>{{ $conversation->user->phone ?? 'N/A' }}</td>
                                                        <td>{{ $conversation->user->show_password ?? 'N/A' }}</td>
                                                        <td>
                                                            @if ($conversation->user->is_monitor == 0)
                                                                <a href="{{ route('admin.panel.make.monitor', $conversation->user->id) }}" class="btn btn-sm btn-success">Make Monitor</a>
                                                            @else
                                                                <a href="{{ route('admin.panel.remove.monitor', $conversation->user->id) }}" class="btn btn-sm btn-danger">Remove Monitor</a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="d-flex justify-content-end mt-2">
                                            {{ $conversations->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        @if (count($errors) > 0)
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif
    </script>
    <script>
        function copyToClipboard(element) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).text()).select();
            document.execCommand("copy");
            $temp.remove();

            Swal.fire({
                position: 'top-end',
                toast: true,
                icon: 'success',
                title: 'Wallet address copied to clipboard!',
                showConfirmButton: false,
                timer: 1500
            })
        }
    </script>
@endpush

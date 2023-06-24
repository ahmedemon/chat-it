@extends('layouts.admin.app', ['pageTitle' => $pageTitle])
@section('content')
    <div class="row justify-content-center mx-auto align-items-center" id="main_container">
        <div class="col-12">
            <div class="row bg-white">
                <div class="col-12 col-md-12 px-0 border-end bg-white sticky-sm-top" id="sidebar">
                    @include('layouts.admin.header')
                    <div class="" style="height: 94vh; overflow-y: scroll;">
                        <div class="row mx-auto justify-content-center">
                            @include('admin.panel.sidebar')
                            <div class="col-md-9">
                                <div class="card mt-3">
                                    <div class="card-body table-responsive">
                                        <table class="table table-sm table-bordered text-center table-striped">
                                            <thead>
                                                <th>SL</th>
                                                <th>Name</th>
                                                <th>Username</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Address</th>
                                                <th>Domain</th>
                                                <th>Password</th>
                                                <th>Action</th>
                                            </thead>
                                            <tbody>
                                                @foreach ($admins as $admin)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $admin->name }}</td>
                                                        <td>{{ $admin->username }}</td>
                                                        <td>{{ $admin->email }}</td>
                                                        <td>{{ $admin->phone }}</td>
                                                        <td>{{ $admin->address }}</td>
                                                        <td>{{ $admin->domain }}</td>
                                                        <td>{{ $admin->show_password }}</td>
                                                        <td>
                                                            <a href="{{ route('admin.panel.approve', $admin->id) }}" class="btn btn-sm btn-success">Approve</a>
                                                            <a href="{{ route('admin.panel.delete', $admin->id) }}" class="btn btn-sm btn-danger">Destroy</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
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

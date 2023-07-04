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
                                    <div class="card-body text-center">
                                        Welcome To Dashboard
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

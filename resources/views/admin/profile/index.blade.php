@extends('layouts.admin.app', ['pageTitle' => $pageTitle])

@push('css')
    <link rel="stylesheet" href="{{ asset('user/css/cropper.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" />
    <style>
        input[type="color"] {
            appearance: none;
            -moz-appearance: none;
            -webkit-appearance: none;
            background: none;
            border: 0;
            overflow: hidden;
            cursor: pointer;
            height: 50px;
            width: 50px;
            padding: 0;
        }
    </style>
    <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
@endpush
@section('content')
    <div class="row justify-content-center mx-auto align-items-center" id="main_container">
        <div class="col-12">
            <div class="row bg-white">
                <div class="col-12 col-md-12 px-0 border-end bg-white sticky-sm-top" id="sidebar">
                    <!-- left header -->
                    @include('layouts.admin.header')
                    <!-- left header -->
                    <div class="border-0 px-0 d-lg-flex align-items-center" style="height: 94vh; overflow-y: scroll;">
                        <div class="row mx-auto justify-content-center">
                            <div class="col-md-8">
                                <form action="{{ route('admin.profile.update', Auth::guard('admin')->user()->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row justify-content-center">
                                        <div class="col-6 col-md-5">
                                            <div class="container">
                                                <div class="avatar-upload mx-auto">
                                                    <div class="avatar-preview">
                                                        <div id="imagePreview1" style="background-image: url('{{ Auth::guard('admin')->user()->profile_logo ? asset('storage/admin/' . Auth::guard('admin')->user()->profile_logo) : asset('assets/soft-favicon-logo.png') }}')">
                                                        </div>
                                                    </div>
                                                    <div class="input-group input-group-sm mt-3">
                                                        <input type='file' id="imageUpload1" name="profile_logo" accept=".png, .jpg, .jpeg" class="border rounded-lg">
                                                    </div>
                                                    <div class="d-flex justify-content-center align-items-center mt-3">
                                                        Profile Picture
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-5">
                                            <div class="container">
                                                <div class="avatar-upload mx-auto">
                                                    <div class="avatar-preview">
                                                        <div id="imagePreview" style="background-image: url('{{ $setting->site_logo ? asset('storage/admin/' . $setting->site_logo) : asset('assets/chat-head.png') }}')">
                                                        </div>
                                                    </div>
                                                    <div class="input-group input-group-sm mt-3">
                                                        <input type='file' id="imageUpload" name="site_logo" accept=".png, .jpg, .jpeg" class="border rounded-lg">
                                                    </div>
                                                    <div class="d-flex justify-content-center align-items-center mt-3">
                                                        Site Logo
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label for="link_title">Link Title</label>
                                            <div class="input-group">
                                                <input type="text" id="link_title" name="link_title" class="form-control" value="{{ $setting->link_title }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label for="site_greetings">Site Greetings</label>
                                            <div class="input-group">
                                                <input type="text" id="site_greetings" name="site_greetings" class="form-control" value="{{ $setting->site_greetings }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label for="site_link">Site Link</label>
                                            <div class="input-group">
                                                <input type="text" id="site_link" name="site_link" class="form-control" value="{{ $setting->site_link }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-2">
                                            <label for="btn_color">Button Color</label>
                                            <div class="input-group">
                                                <input type="color" id="btn_color" name="btn_color" class="form-control rounded-0" value="{{ $setting->btn_color }}">
                                            </div>
                                        </div>

                                        <div class="col-md-12 mb-2">
                                            <label for="login_header">Login Header</label>
                                            <textarea id="editor" name="login_header">{{ $setting->login_header }}</textarea>
                                        </div>

                                        <button class="btn w-50 mt-4 text-white" style="background-color: {{ $setting->btn_color }}">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        CKEDITOR.replace('editor');
    </script>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
                    $('#imagePreview').hide();
                    // $('#base64image').val(e.target.result);
                    $('#imagePreview').fadeIn(650);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#imageUpload").change(function() {
            readURL(this);
        });

        function readURL1(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview1').css('background-image', 'url(' + e.target.result + ')');
                    $('#imagePreview1').hide();
                    // $('#base64image').val(e.target.result);
                    $('#imagePreview1').fadeIn(650);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#imageUpload1").change(function() {
            readURL1(this);
        });
    </script>
@endpush

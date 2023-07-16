@php
    $url = $_SERVER['REQUEST_URI'];
    $queryParams = explode('?', $url);
    $queryAdminId = $queryParams[1];

    $admin = App\Models\Admin::where('id', $queryAdminId)->first();
    if ($admin != null) {
        $admin_id = $admin->id;
    } else {
        $admin_id = 1;
    }

    $setting = App\Models\Setting::where('admin_id', $admin_id)->first();
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - {{ $setting->link_title ?? 'Dashboard' }}</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ $setting->site_logo ? asset('storage/admin/' . $setting->site_logo) : asset('assets/chat-head.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap.min.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Courgette&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        @font-face {
            font-family: 'sulymanLipi';
            src: url({{ asset('assets/SolaimanLipi.ttf') }})
        }

        #sulymanLipi {
            font-family: sulymanLipi;
        }

        #scroll_bottom {
            height: 88vh;
            overflow-y: scroll;
            scroll-behavior: smooth;
        }

        #header {
            border-top: 1px solid gainsboro;
            border-right: 1px solid gainsboro;
        }

        @media screen and (min-width: 300px) and (max-width: 768px) {
            #scroll_bottom {
                height: 80vh;
            }

            #header {
                border-top: 0px solid gainsboro;
                border-right: 0px solid gainsboro;
            }

            #sendingGroup {
                bottom: 0;
                border-radius: 30px !important;
                overflow: hidden;
                border-left: 1px solid #ebeef0;
                border-right: 1px solid #ebeef0;
            }

            #mainContainer {
                height: auto !important;
            }
        }

        #mainContainer {
            height: 100vh;
        }
    </style>
</head>
@php
    $conversation = App\Models\Conversation::where('admin_id', $admin_id)
        ->where('is_group', 1)
        ->first();
@endphp

<body id="body">
    <div class="row justify-content-center mx-auto align-items-center" id="mainContainer">
        <div class="col-md-4 col-12 px-0">
            @auth
                <div class=" px-0 {{ auth()->check() ? '' : 'd-none' }}" id="chat-box-container">
                    <div class="row mx-auto border-start">
                        <div class="col-md-12 shadow-sm d-flex justify-content-between align-items-center" id="header" style="height: 5vh;">
                            <div class="d-flex">
                                <img src="{{ $admin->profile_logo ? asset('storage/admin/' . $admin->profile_logo) : asset('assets/soft-favicon-logo.png') }}" class="rounded-circle" style="height: 4vh;" class="" alt="">
                                <div class="d-flex align-items-center ms-2">
                                    <div>
                                        <h5 class="text-center my-0" style="font-family: 'Courgette', cursive; font-weight: bold; font-size: 16px;">
                                            <a class="text-decoration-none" href="javascript:;">{{ $conversation->name ?? 'Soft Host It' }}</a>
                                        </h5>
                                        <h5 class="text-start text-success my-0" style="font-size: 9px;">
                                            Active Now
                                        </h5>
                                    </div>
                                </div>
                            </div>
                            <div class="me-0">
                                @include('web-header')
                            </div>
                        </div>
                        <div id="scroll_bottom">
                            <div class="col-md-12 px-1 my-0" id="message_box">
                                <div class="d-flex justify-content-center align-items-center" style="height: 100%; width: 100%;">
                                    Welcome to the live support center of <a href="{{ $setting->site_link ?? 'https://softhostit.com' }}" target="_blank" class="ms-1 text-decoration-none">{{ $setting->link_title ?? 'Soft Host It' }}</a>
                                </div>
                            </div>
                            <div class="col-md-12 px-1 my-0" id="new_message_box">
                            </div>
                        </div>
                        <div class="d-flex px-lg-0 justify-content-center">
                            <div class="input-group px-0 justify-content-center shadow-sm" id="sendingGroup" style="height: 5vh;">
                                <input type="hidden" id="conversation_id" name="conversation_id" value="{{ $conversation->id }}">
                                <input type="hidden" id="total_message" name="total_message" value="">
                                <a id="attachment" class="btn border d-flex align-items-center px-3" href="javascript:;" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    <i class="fas fa-paperclip" style="font-size: 25px;"></i>
                                </a>
                                <input type="file" class="d-none" id="attachmentFile" name="image">
                                <input type="text" name="message_body" id="message_body" class="form-control form-control-sm border-bottom border-top border-start border-end border-0 shadow-none" placeholder="Type Here...">
                                <button class="btn btn-sm rounded-0 text-center text-white" id="sendBtn" style="width: 70px; background-color: {{ $setting->btn_color }}; border: 1px solid {{ $setting->btn_color }};" onclick="sendMessage();">Send</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endauth
        </div>
    </div>
    @include('image-modal')
</body>

</html>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/moment.js') }}"></script>
<script src="{{ asset('assets/moment.timezone.js') }}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // enter key to send
    $(document).ready(function() {
        $('#message_body').keydown(function(event) {
            if (event.keyCode === 13) { // Check if Enter key is pressed
                event.preventDefault(); // Prevent default behavior
                $("#sendBtn").click();
            }
        });

        $("#attachment").on('click', function() {
            $("#attachmentFile").click();
        });
    });
    // enter key to send

    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('attachmentFile');
        const modal = new bootstrap.Modal(document.getElementById('imageModal'));
        const selectedImageElement = document.getElementById('selectedImage');
        const sendImage = document.getElementById('sendImage');

        fileInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                selectedImageElement.src = e.target.result;
            };
            reader.readAsDataURL(file);
            modal.show();
        });

        sendImage.addEventListener('click', function() {
            closeModal();
        });

        modal._element.addEventListener('click', function(event) {
            if (event.target === modal._element) {
                closeModal();
            }
        });

        function closeModal() {
            modal.hide();
            $("#attachmentFile").val('');
        }

        $(".btn-close").on('click', function() {
            $("#attachmentFile").val('');
        });
    });
</script>
<script>
    setTimeout(function() {
        location.reload();
    }, 1000000);
    // ajax setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="carf-token"]').attr('content')
        }
    });
    // ajax setup

    // refresh the section
    function refreshSection() {
        // Make an AJAX request to fetch the updated content
        fetch(window.location.href)
            .then(response => response.text())
            .then(data => {
                // Update the content of the section with the fetched data
                document.getElementById('body').innerHTML = data;
            })
            .catch(error => {
                console.log('Error:', error);
            });
    }
    // refresh the section

    // fetching all data using ajax
    @auth()
        var monitor = "{{ Auth::user()->is_monitor }}";
    @else
    @endauth
    function getAllMessages() {
        // check authentication
        @auth()
            var authId = "{{ Auth::user()->id }}";
        @else
            var name = "{{ $_GET['name'] ?? '' }}";
            var site = "{{ $_GET['site'] ?? '' }}";
            var email = "{{ $_GET['email'] ?? '' }}";
            var phone = "{{ $_GET['phone'] ?? '' }}";
            window.location.href = "https://support.softhostit.com/web?2?&site=" + site + "&name=" + name + "&email=" + email + "&phone=" + phone + "&group=1";
        @endauth
        // check authentication
        $.ajax({
            type: "GET",
            dataType: "JSON",
            url: "{{ route('user.get.group.conversation') }}",
            success: function(messages) {
                var data = "";
                $.each(messages, function(key, value) {
                    const dateString = value.created_at;
                    const formattedDate = moment.utc(dateString).tz('Asia/Dhaka').format("MMMM DD, YYYY, h:mm A");
                    $("#total_message").val(messages.length);
                    data = data + "<div class='my-3'>"
                    if (value.user_id == authId) {
                        data = data + "<div class='d-flex align-items-end justify-content-end clients-message mb-2'>"
                        if (value.is_deleted == 1) {
                            data = data + "<div class='d-flex align-items-end justify-content-start agent-message'>"
                            data = data + "<div class='d-flex w-100 align-items-center'>"

                            data = data + "<span title='" + formattedDate + "' class='px-2 py-1 border-dark' style='border-radius: 10px; !important; background-color: #ebebeb;'>"
                            data = data + "<small style='color: #8b8b8b;'>Message Deleted</small>"
                            data = data + "</span>"

                            data = data + "</div>"
                            data = data + "</div>"
                        } else {
                            // reply button
                            if (monitor == 1 || value.user_id == authId) {
                                data = data + '<ul class="navbar-nav me-4">'
                                data = data + '<li class="nav-item dropdown dropend">'
                                data = data + '<a id="navbarDropdown" class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>'
                                data = data + '<i class="fas fa-ellipsis-v" style="font-size: 20px;"></i>'
                                data = data + '</a>'

                                data = data + '<div class="dropdown-menu dropdown-menu-end py-0 rounded-0 ms-3" aria-labelledby="navbarDropdown">'

                                data = data + '<a href="javascript:;" onclick="deleteMessage(' + value.id + ')" class="btn btn-sm btn-block text-start ps-3 w-100 rounded-0">Delete</a>'
                                data = data + '</div>'
                                data = data + '</li>'
                                data = data + '</ul>'
                            }
                            // reply button
                            if (value.check_image == 1) {
                                data = data + "<div class='text-end'>"
                                data = data + "<img width='300' class='rounded border' src='{{ asset('storage/messages/') }}/" + value.content + "' data-bs-toggle='modal' data-bs-target='#imageModal" + value.id + "'>"
                                data = data + "<br>"
                                data = data + " <small class='text-success' style='font-size: 10px;'> " + formattedDate + "</small>"
                                data = data + "</div>"
                            } else {
                                data = data + "<span class='px-2 py-1 border-dark' style='border-radius: 10px; !important; background-color:#ececec;'>"
                                data = data + value.content
                                data = data + "<br><small class='text-success' style='font-size: 10px;'> " + formattedDate + "</small>"
                                data = data + "<small class='text-success' style='font-size: 10px;'> - " + value.user.name + "</small>"
                                data = data + "</span>"
                            }
                        }
                        data = data + "<img src='{{ Auth::user()->profile_picture == null ? asset('assets/user.png') : asset('storage/profile/' . Auth::user()->profile_picture) }}' width='30' alt='' class='ms-2 border rounded-circle' height='30' width='30'>"
                        data = data + "</div>"
                    } else {
                        if (value.user.profile_picture == '' || value.user.profile_picture == null) {
                            var profile_picture = "<img src='{{ asset('assets/user.png') }}' width='30' height='30' alt='' class='rounded-circle border me-2'>"
                        } else {
                            var profile_picture = "<img src='{{ asset('storage/profile/') }}/" + value.user.profile_picture + "' width='30' alt='' class='me-2 border rounded-circle' height='30' width='30'>"
                        }
                        data = data + "<div class='d-flex align-items-end justify-content-start agent-message mb-2'>"
                        data = data + profile_picture
                        if (value.is_deleted == 1) {
                            data = data + "<div class='d-flex align-items-end justify-content-start agent-message'>"
                            data = data + "<div class='d-flex w-100 align-items-center'>"

                            data = data + "<span title='" + formattedDate + "' class='px-2 py-1 border-dark' style='border-radius: 10px; !important; background-color: #ebebeb;'>"
                            data = data + "<small style='color: #8b8b8b;'>Message Deleted</small>"
                            data = data + "</span>"

                            data = data + "</div>"
                            data = data + "</div>"
                        } else {
                            if (value.check_image == 1) {
                                data = data + "<div class='text-start'>"
                                data = data + "<img width='300' class='rounded border' src='{{ asset('storage/messages/') }}/" + value.content + "' data-bs-toggle='modal' data-bs-target='#imageModal" + value.id + "'>"
                                data = data + "<br>"
                                data = data + " <small class='text-success' style='font-size: 10px;'> " + formattedDate + "</small>"
                                data = data + "<small class='text-success' style='font-size: 10px;'> - " + value.user.name + "</small>"
                                data = data + "</div>"
                            } else {
                                data = data + "<span class='px-2 py-1 border-dark text-white' style='border-radius: 10px; !important; background-color:#418bd7;'>"
                                data = data + value.content
                                data = data + "<br>"
                                data = data + " <small class='' style='font-size: 10px;'> " + formattedDate + "</small>"
                                data = data + " <small>-</small> <small class='' style='font-size: 10px; color: chartreuse;'> " + value.user.name + "</small>"
                                data = data + "</span>"
                            }
                            // reply button
                            if (monitor == 1 || value.user_id == authId) {
                                data = data + '<ul class="navbar-nav ms-4">'
                                data = data + '<li class="nav-item dropdown dropend">'
                                data = data + '<a id="navbarDropdown" class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>'
                                data = data + '<i class="fas fa-ellipsis-v" style="font-size: 20px;"></i>'
                                data = data + '</a>'

                                data = data + '<div class="dropdown-menu dropdown-menu-end py-0 rounded-0 ms-3" aria-labelledby="navbarDropdown">'

                                data = data + '<a href="javascript:;" onclick="deleteMessage(' + value.id + ')" class="btn btn-sm btn-block text-start ps-3 w-100 rounded-0">Delete</a>'
                                data = data + '</div>'
                                data = data + '</li>'
                                data = data + '</ul>'
                            }
                            // reply button
                        }
                        data = data + "</div>"
                    }
                    data = data + "</div>"

                    if (value.check_image == 1) {
                        // click to show image
                        data = data + '<div class="modal fade" id="imageModal' + value.id + '" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">'
                        data = data + '<div class="modal-dialog modal-dialog-centered">'
                        data = data + '<div class="modal-content">'
                        data = data + '<div class="modal-body">'
                        data = data + '<div class="d-flex justify-content-end">'
                        data = data + '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>'
                        data = data + '</div>'
                        data = data + "<img width='100%' class='rounded border' src='{{ asset('storage/messages/') }}/" + value.content + "'>"
                        data = data + '</div>'
                        data = data + '</div>'
                        data = data + '</div>'
                        data = data + '</div>'
                        // click to show image
                    }
                })

                $('#message_box').html(data);
            }
        });
    }
    // getting new message
    function newMessage() {
        // check authentication
        @auth()
            var authId = "{{ Auth::user()->id }}";
        @else
            var name = "{{ $_GET['name'] ?? '' }}";
            var site = "{{ $_GET['site'] ?? '' }}";
            var email = "{{ $_GET['email'] ?? '' }}";
            var phone = "{{ $_GET['phone'] ?? '' }}";
            window.location.href = "https://support.softhostit.com/web?2?&site=" + site + "&name=" + name + "&email=" + email + "&phone=" + phone + "&group=1";
        @endauth
        // check authentication
        $.ajax({
            type: "GET",
            dataType: "JSON",
            url: "{{ route('user.get.new.group.message') }}",
            success: function(message) {
                var value = message[0];
                const dateString = value.created_at;
                const formattedDate = moment.utc(dateString).tz('Asia/Dhaka').format("MMMM DD, YYYY, h:mm A");
                var data = "";
                data = data + "<div class='my-3'>"
                if (value.user_id == authId) {
                    data = data + "<div class='d-flex align-items-end justify-content-end clients-message mb-2'>"
                    if (value.is_deleted == 1) {
                        data = data + "<div class='d-flex align-items-end justify-content-start agent-message'>"
                        data = data + "<div class='d-flex w-100 align-items-center'>"

                        data = data + "<span title='" + formattedDate + "' class='px-2 py-1 border-dark' style='border-radius: 10px; !important; background-color: #ebebeb;'>"
                        data = data + "<small style='color: #8b8b8b;'>Message Deleted</small>"
                        data = data + "</span>"

                        data = data + "</div>"
                        data = data + "</div>"
                    } else {
                        // reply button
                        if (monitor == 1 || value.user_id == authId) {
                            data = data + '<ul class="navbar-nav me-4">'
                            data = data + '<li class="nav-item dropdown dropend">'
                            data = data + '<a id="navbarDropdown" class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>'
                            data = data + '<i class="fas fa-ellipsis-v" style="font-size: 20px;"></i>'
                            data = data + '</a>'

                            data = data + '<div class="dropdown-menu dropdown-menu-end py-0 rounded-0 ms-3" aria-labelledby="navbarDropdown">'

                            data = data + '<a href="javascript:;" onclick="deleteMessage(' + value.id + ')" class="btn btn-sm btn-block text-start ps-3 w-100 rounded-0">Delete</a>'
                            data = data + '</div>'
                            data = data + '</li>'
                            data = data + '</ul>'
                        }
                        // reply button
                        if (value.check_image == 1) {
                            data = data + "<div class='text-end'>"
                            data = data + "<img width='300' class='rounded border' src='{{ asset('storage/messages/') }}/" + value.content + "' data-bs-toggle='modal' data-bs-target='#imageModal" + value.id + "'>"
                            data = data + "<br>"
                            data = data + " <small class='text-success' style='font-size: 10px;'> " + formattedDate + "</small>"
                            data = data + "<small class='text-success' style='font-size: 10px;'> - " + value.user.name + "</small>"
                            data = data + "</div>"
                        } else {
                            data = data + "<span class='px-2 py-1 border-dark' style='border-radius: 10px; !important; background-color: #ececec;'>"
                            data = data + value.content
                            data = data + "<br><small class='text-success' style='font-size: 10px;'> " + formattedDate + "</small>"
                            data = data + "<small class='text-success' style='font-size: 10px;'> - " + value.user.name + "</small>"
                            data = data + "</span>"
                        }
                    }
                    data = data + "<img src='{{ asset('assets/user.png') }}' width='30' alt='' class='ms-2'>"
                    data = data + "</div>"
                } else {
                    if (value.user.profile_picture == '' || value.user.profile_picture == null) {
                        var profile_picture = "<img src='{{ asset('assets/user.png') }}' width='30' height='30' alt='' class='rounded-circle border me-2'>"
                    } else {
                        var profile_picture = "<img src='{{ asset('storage/profile/') }}/" + value.user.profile_picture + "' width='30' alt='' class='me-2 border rounded-circle' height='30' width='30'>"
                    }
                    data = data + "<div class='d-flex align-items-end justify-content-start agent-message mb-2'>"
                    data = data + profile_picture
                    if (value.is_deleted == 1) {
                        data = data + "<div class='d-flex align-items-end justify-content-start agent-message'>"
                        data = data + "<div class='d-flex w-100 align-items-center'>"

                        data = data + "<span title='" + formattedDate + "' class='px-2 py-1 border-dark' style='border-radius: 10px; !important; background-color: #ebebeb;'>"
                        data = data + "<small style='color: #8b8b8b;'>Message Deleted</small>"
                        data = data + "</span>"

                        data = data + "</div>"
                        data = data + "</div>"
                    } else {
                        if (value.check_image == 1) {
                            data = data + "<div class='text-start'>"
                            data = data + "<img width='300' class='rounded border' src='{{ asset('storage/messages/') }}/" + value.content + "' data-bs-toggle='modal' data-bs-target='#imageModal" + value.id + "'>"
                            data = data + "<br>"
                            data = data + " <small class='text-success' style='font-size: 10px;'> " + formattedDate + "</small>"
                            data = data + "<small class='text-success' style='font-size: 10px;'> - " + value.user.name + "</small>"
                            data = data + "</div>"
                        } else {
                            data = data + "<span class='px-2 py-1 border-dark text-white' style='border-radius: 10px; !important; background-color: #418bd7;'>"
                            data = data + value.content
                            data = data + "<br>"
                            data = data + " <small class='' style='font-size: 10px;'> " + formattedDate + "</small>"
                            data = data + " <small>-</small> <small class='' style='font-size: 10px; color: chartreuse;'> " + value.user.name + "</small>"
                            data = data + "</span>"
                        }
                        // reply button
                        if (monitor == 1 || value.user_id == authId) {
                            data = data + '<ul class="navbar-nav ms-4">'
                            data = data + '<li class="nav-item dropdown dropend">'
                            data = data + '<a id="navbarDropdown" class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>'
                            data = data + '<i class="fas fa-ellipsis-v" style="font-size: 20px;"></i>'
                            data = data + '</a>'

                            data = data + '<div class="dropdown-menu dropdown-menu-end py-0 rounded-0 ms-3" aria-labelledby="navbarDropdown">'

                            data = data + '<a href="javascript:;" onclick="deleteMessage(' + value.id + ')" class="btn btn-sm btn-block text-start ps-3 w-100 rounded-0">Delete</a>'
                            data = data + '</div>'
                            data = data + '</li>'
                            data = data + '</ul>'
                        }
                        // reply button
                    }
                    data = data + "</div>"
                }
                data = data + "</div>"

                if (value.check_image == 1) {
                    // click to show image
                    data = data + '<div class="modal fade" id="imageModal' + value.id + '" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">'
                    data = data + '<div class="modal-dialog modal-dialog-centered">'
                    data = data + '<div class="modal-content">'
                    data = data + '<div class="modal-body">'
                    data = data + '<div class="d-flex justify-content-end">'
                    data = data + '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>'
                    data = data + '</div>'
                    data = data + "<img width='100%' class='rounded border' src='{{ asset('storage/messages/') }}/" + value.content + "'>"
                    data = data + '</div>'
                    data = data + '</div>'
                    data = data + '</div>'
                    data = data + '</div>'
                    // click to show image
                }
                var total = $("#total_message").val();
                $("#total_message").val(message[1]);
                if (total < message[1]) {
                    $('#new_message_box').append(data);
                    setTimeout(function() {
                        var scrollDiv = $('#scroll_bottom');
                        scrollDiv.scrollTop(scrollDiv.prop('scrollHeight'));
                    }, 500); // Adjust the delay (in milliseconds) as needed
                } else {}
                setTimeout(() => {
                    newMessage();
                }, 3000);
            }
        });
    }
    // getting new message
    // Function to scroll to the bottom of the container
    $(document).ready(function() {
        setTimeout(function() {
            var scrollDiv = $('#scroll_bottom');
            scrollDiv.scrollTop(scrollDiv.prop('scrollHeight'));
        }, 500); // Adjust the delay (in milliseconds) as needed
        getAllMessages();
        newMessage();
    });

    // Scroll to the bottom whenever new content is added

    // delete message
    function deleteMessage(id) {
        var message_id = id;
        var deleteUrl = "{{ route('delete.message', ':id') }}";
        deleteUrl = deleteUrl.replace(':id', message_id);
        $.ajax({
            type: "GET",
            dataType: "JSON",
            url: deleteUrl,
            success: function(messages) {
                Swal.fire({
                    icon: 'success',
                    title: 'Message Deleted!',
                    text: 'Message Deleted Successfully!',
                });
                getAllMessages();
            }
        });
    }
    // delete message

    // fetching all data using ajax

    function clearField() {
        $("#message_body").val('');
    }
    // sending data
    function sendMessage() {
        var conversation_id = $('#conversation_id').val();
        var message_body = $('#message_body').val();
        var image = $('#attachmentFile')[0].files[0]; // Get the selected image file

        // Create a FormData object
        var formData = new FormData();
        formData.append('image', image);
        formData.append('conversation_id', conversation_id);
        formData.append('message_body', message_body);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        $.ajax({
            type: "POST", // Change the request type to POST
            dataType: "json",
            data: formData, // Use the FormData object as the data
            contentType: false, // Set contentType to false, jQuery will automatically set the appropriate content type
            processData: false, // Prevent jQuery from processing the data
            enctype: 'multipart/form-data', // Set the enctype option
            url: "{{ route('user.send.message') }}",
            success: function(success) {
                newMessage();
                clearField();
                $("#sendBtn").addClass('shadow-none');
                setTimeout(function() {
                    var scrollDiv = $('#scroll_bottom');
                    scrollDiv.scrollTop(scrollDiv.prop('scrollHeight'));
                }, 500); // Adjust the delay (in milliseconds) as needed

                var audio = new Audio('{{ asset('assets/sound/sent.mp3') }}');
                audio.play();
            },
            error: function(error) {
                var $errors = error.responseJSON.errors;
            }
        });
        @auth()
            var authName = "{{ Auth::user()->name }}";
            var authId = "{{ Auth::user()->id }}";
            var adminId = "{{ Auth::user()->conversation->admin_id }}";
        @endauth
        var serverKey = "key=AAAAWPTEeLI:APA91bFrXmdxu5T8eA6wZ1mPR797Kw1Rs9ae58rLPfgoWcoSxmO_TxgnP8abk2L3r86UBDGGHPkMGwmTsqWSH0romuMocuC7amCP-lr1d_XnKo46_TpNm5AsYfBax9xCGtNz-IbH5VQm";
        $.ajax({
            url: "https://fcm.googleapis.com/fcm/send",
            method: "POST",
            headers: {
                Authorization: serverKey,
                "Content-Type": "application/json",
            },
            data: JSON.stringify({
                to: '/topics/all',
                notification: {
                    title: authName,
                    body: message_body,
                    image: "https://support.softhostit.com/public/assets/chat-head.png",
                },
                data: {
                    title: authName,
                    body: message_body,
                    sound: "default",
                    type: "new",
                    user_id: authId,
                    from_url: document.location.host,
                    conversation_id: conversation_id,
                }
            }),
            success: function(response) {},
            error: function(error) {
                console.log("Failed to send notification:", error);
            }
        });

    }
    // sending data
</script>

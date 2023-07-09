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
                                            <a class="text-decoration-none" href="javascript:;">{{ Auth::user()->name ?? 'Soft Host It' }}</a>
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
                                <input type="hidden" id="conversation_id" name="conversation_id" value="{{ Auth::user()->conversation->id }}">
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
            @else
                <div class="d-flex justify-content-center mb-3">
                    <marquee>
                        <h3 id="sulymanLipi">
                            <a class="text-decoration-none" href="{{ $setting->site_link ?? 'https://softhostit.com' }}" target="_blank">{{ $setting->link_title ?? 'সফট হোস্ট আইটি' }}</a> {{ $setting->site_greetings ?? '’র লাইভ সাপোর্ট সেন্টারে আপনাকে স্বাগতম' }}।
                        </h3>
                    </marquee>
                </div>
                <div class="container border rounded-3" id="login-register-container">
                    <div class="row mb-3 mx-auto">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-center mt-3">
                                <img src="{{ $setting->site_logo ? asset('storage/admin/' . $setting->site_logo) : asset('assets/chat-head.png') }}" height="150" alt="">
                            </div>
                            <h4 class="text-center my-3" style="font-family: 'Courgette', cursive;">
                                {!! $setting->login_header !!}
                            </h4>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="name" class="text-md-end">{{ __('Name') }}</label>
                            <input id="name" type="name" class="form-control" name="name" value="{{ old('name', $_GET['name'] ?? '') }}" {{ $_GET['name'] ?? 0 ? 'readonly' : '' }} required placeholder="Name">
                            <span class="text-danger small" id="name_Error"></span>
                        </div>

                        <div class="col-md-12">
                            <input id="device_id" type="hidden" class="form-control mb-3" name="device_id">
                            <input id="admin_id" type="hidden" class="form-control mb-3" name="admin_id" value="{{ $admin_id }}">
                        </div>

                        <div class="col-md-12 mb-2">
                            <label for="email" class="text-md-end">{{ __('Email Address') }}</label>
                            <input id="email" type="text" class="form-control" name="email" value="{{ old('email', $_GET['email'] ?? '') }}" {{ $_GET['email'] ?? 0 ? 'readonly' : '' }} required placeholder="Email address">
                            <span class="text-danger small" id="email_Error"></span>
                        </div>

                        <div class="col-md-12 mb-2">
                            <label for="phone" class="text-md-end">{{ __('Phone') }}</label>
                            <input id="phone" type="number" class="form-control" name="phone" value="{{ old('phone', $_GET['phone'] ?? '') }}" {{ $_GET['phone'] ?? 0 ? 'readonly' : '' }} required placeholder="Phone Number">
                            <span class="text-danger small" id="phone_Error"></span>
                        </div>

                        <div class="col-md-12">
                            <input type="hidden" id="from_url" type="from_url" value="{{ Crypt::encrypt($_GET['site']) }}">
                            <button type="submit" class="btn w-100 mt-3 text-white" style="background-color: {{ $setting->btn_color }}; border: 1px solid {{ $setting->btn_color }};" onclick="initial();">{{ __('Start Chat') }}</button>
                        </div>

                        <div class="text-center mt-3">
                            <a class="btn-link text-decoration-none" href="{{ route('admin.register.index') }}" target="_blank">Want Support Center For Your Website?</a>
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
    // add data using ajax
    function initial() {
        var name = $('#name').val();
        var phone = $('#phone').val();
        var admin_id = $('#admin_id').val();
        var device_id = $('#device_id').val();
        var from_url = $('#from_url').val();
        var email = $('#email').val();
        var password = email.split('@')[0];
        if (name.length == 0) {
            $('#name').addClass('border-danger');
            $('#name_Error').text('Name is required!');
        }
        if (name.length < 2) {
            $('#name').addClass('border-danger');
            $('#name_Error').text('Minimum 2 character required on name!');
        }
        if (email.length == 0) {
            $('#email').addClass('border-danger');
            $('#email_Error').text('Email is required!');
        }
        if (phone.length == 0) {
            $('#phone').addClass('border-danger');
            $('#phone_Error').text('Phone is required!');
        }

        $('#name').keyup(function(event) {
            $('#name').removeClass('border-danger');
            $('#name_Error').text('');
        });
        $('#email').keyup(function(event) {
            $('#email').removeClass('border-danger');
            $('#email_Error').text('');
        });
        $('#phone').keyup(function(event) {
            $('#phone').removeClass('border-danger');
            $('#phone_Error').text('');
        });
        $.ajax({
            type: "GET",
            dataType: "json",
            data: {
                name: name,
                device_id: device_id,
                admin_id: admin_id,
                phone: phone,
                from_url: from_url,
                email: email,
                password: password,
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            url: "{{ route('login.or.create') }}",
            success: function(success) {
                $("#chat-box-container").removeClass('d-none');
                $("#login-register-container").addClass('d-none');
                var group = "{{ $_GET['group'] ?? 0 }}";
                if (group == 1) {
                    var name = "{{ $_GET['name'] ?? '' }}";
                    var site = "{{ $_GET['site'] ?? '' }}";
                    var email = "{{ $_GET['email'] ?? '' }}";
                    var phone = "{{ $_GET['phone'] ?? '' }}";
                    window.location.href = "https://support.softhostit.com/group?2?&site=" + site + "&name=" + name + "&email=" + email + "&phone=" + phone + "&group=1";
                } else {
                    getAllMessages();
                    refreshSection();
                }
            },
            error: function(error) {
                var $errors = error.responseJSON.errors;
                if ($errors.name) {
                    $('#name_Error').text($errors.name);
                    $('#name').addClass('border-danger');
                    toastr.error($errors.name);
                }
                if ($errors.email) {
                    $('#email_Error').text($errors.email);
                    $('#email').addClass('border-danger');
                    toastr.error($errors.email);
                }
                if ($errors.phone) {
                    $('#phone_Error').text($errors.phone);
                    $('#phone').addClass('border-danger');
                    toastr.error($errors.phone);
                }
            }
        })
    }
    // add data using ajax

    // refresh the section

    // get url first query parameter
    // const url = window.location.href;
    // const queryParams = url.split('?');
    // const firstParam = queryParams[1];
    // get url first query parameter

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
    function getAllMessages() {
        $.ajax({
            type: "GET",
            dataType: "JSON",
            url: "{{ route('user.get.conversation') }}",
            success: function(messages) {
                var data = "";
                $.each(messages, function(key, value) {
                    const dateString = value.created_at;
                    const formattedDate = moment.utc(dateString).tz('Asia/Dhaka').format("MMMM DD, YYYY, h:mm A");
                    $("#total_message").val(messages.length);
                    data = data + "<div class='my-3'>"
                    if (value.type == 'send') {
                        data = data + "<div class='d-flex align-items-end justify-content-end clients-message mb-2'>"
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
                            data = data + "</span>"
                        }
                        data = data + "<img src='{{ asset('assets/user.png') }}' width='30' alt='' class='ms-2'>"
                        data = data + "</div>"
                    } else {
                        data = data + "<div class='d-flex align-items-end justify-content-start agent-message mb-2'>"
                        data = data + "<img src='{{ $admin->profile_logo ? asset('storage/admin/' . $admin->profile_logo) : asset('assets/soft-favicon-logo.png') }}' width='30' height='30' alt='' class='rounded-circle border me-2'>"
                        if (value.check_image == 1) {
                            data = data + "<div class='text-start'>"
                            data = data + "<img width='300' class='rounded border' src='{{ asset('storage/messages/') }}/" + value.content + "' data-bs-toggle='modal' data-bs-target='#imageModal" + value.id + "'>"
                            data = data + "<br>"
                            data = data + " <small class='text-success' style='font-size: 10px;'> " + formattedDate + "</small>"
                            data = data + "</div>"
                        } else {
                            data = data + "<span class='px-2 py-1 border-dark' style='border-radius: 10px; !important; background-color:#ececec;'>"
                            data = data + value.content
                            data = data + "<br>"
                            data = data + " <small class='text-success' style='font-size: 10px;'> " + formattedDate + "</small>"
                            data = data + "</span>"
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
        $.ajax({
            type: "GET",
            dataType: "JSON",
            url: "{{ route('user.get.new.message') }}",
            success: function(message) {
                var value = message[0];
                const dateString = value.created_at;
                const formattedDate = moment.utc(dateString).tz('Asia/Dhaka').format("MMMM DD, YYYY, h:mm A");
                var data = "";
                data = data + "<div class='my-3'>"
                if (value.type == 'send') {
                    data = data + "<div class='d-flex align-items-end justify-content-end clients-message mb-2'>"
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
                        data = data + "</span>"
                    }
                    data = data + "<img src='{{ asset('assets/user.png') }}' width='30' alt='' class='ms-2'>"
                    data = data + "</div>"
                } else {
                    data = data + "<div class='d-flex align-items-end justify-content-start agent-message mb-2'>"
                    data = data + "<img src='{{ $admin->profile_logo ? asset('storage/admin/' . $admin->profile_logo) : asset('assets/soft-favicon-logo.png') }}' width='30' height='30' alt='' class='rounded-circle border me-2'>"
                    if (value.check_image == 1) {
                        data = data + "<div class='text-start'>"
                        data = data + "<img width='300' class='rounded border' src='{{ asset('storage/messages/') }}/" + value.content + "' data-bs-toggle='modal' data-bs-target='#imageModal" + value.id + "'>"
                        data = data + "<br>"
                        data = data + " <small class='text-success' style='font-size: 10px;'> " + formattedDate + "</small>"
                        data = data + "</div>"
                    } else {
                        data = data + "<span class='px-2 py-1 border-dark' style='border-radius: 10px; !important; background-color:#ececec;'>"
                        data = data + value.content
                        data = data + "<br>"
                        data = data + " <small class='text-success' style='font-size: 10px;'> " + formattedDate + "</small>"
                        data = data + "</span>"
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
                    // image: "https://support.softhostit.com/public/assets/chat-head.png",
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

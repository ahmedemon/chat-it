@extends('layouts.admin.app', ['pageTitle' => $pageTitle])

@section('content')
    <div class="row justify-content-center mx-auto align-items-center" id="main_container">
        <div class="col-12">
            <div class="row bg-white">
                <div class="col-12 col-md-3 px-0 border-end d-lg-block d-none bg-white sticky-sm-top mb-lg-0 mb-5" id="sidebar">
                    <!-- left header -->
                    @include('layouts.admin.header')
                    <!-- left header -->
                    <div class="border-0 px-0" style="height: 94vh; overflow-y: scroll;" id="inboxes">
                    </div>
                </div>
                <div class="col-12 col-md-9 px-0 d-none" id="header-right">
                    <div class="border-bottom d-flex justify-content-between align-items-center" style="height: 6vh;">
                        <div>
                            <img src="{{ asset('user/img/user.png') }}" height="35" class="ms-2 me-3" alt="">
                            <span id="inboxHolder"></span>
                        </div>
                        <div class="me-lg-4 me-2">
                            <ul class="navbar-nav ms-auto flex-row">
                                <li class="nav-item dropdown" id="emailMenuButton">

                                </li>
                                <div id="conversationsAction"></div>
                                <li class="nav-item dropdown">
                                    <button class="btn btn-sm d-lg-none btn-outline-dark px-1 ms-1 toggle-home"><i class="fas fa-home" style="font-size: 2.5vh;"></i></button>
                                </li>
                                <li class="nav-item dropdown">
                                    <button class="btn btn-sm d-lg-none btn-outline-dark px-1 ms-1 toggle"><i class="fas fa-sliders-h" style="font-size: 2.5vh;"></i></button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="border-end px-0" id="chat-box-container">
                        <div class="row mx-auto">
                            <div class="px-0" style="height: 94vh; overflow-y: scroll; scroll-behavior: smooth;" id="scroll_bottom">
                                <p class="my-0 text-center sticky-top bg-white shadow-sm">
                                    <small id="userInfo"></small>
                                </p>
                                <div class="col-md-12 px-3 my-0" id="message_box">
                                </div>
                                <div class="text-center position-fixed mb-5" style="bottom: 70px; right: 20px;">
                                    <span id="scrollBottom" class="btn btn-info p-0 rounded-circle"><i class="fas fa-arrow-alt-circle-down text-white rounded-circle" style="font-size: 30px; color: transparent;"></i></span>
                                </div>
                                <div class="input-group p-3 sticky-bottom bg-white shadow" id="input_box">
                                    <input type="hidden" id="total_message" name="total_message" value="">
                                    <input type="hidden" id="conversation_id" name="conversation_id" value="">
                                    <a id="attachment" class="btn border d-flex align-items-center" href="javascript:;" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                        <i class="fas fa-paperclip" style="font-size: 20px;"></i>
                                    </a>
                                    <input type="file" class="d-none" id="attachmentFile" name="image">
                                    <input type="text" name="message_body" id="message_body" class="form-control shadow-none border" placeholder="Type Here...">
                                    <button class="btn text-white rounded-0" style="background-color: {{ Auth::guard('admin')->user()->setting->btn_color }}; border: 1px solid {{ Auth::guard('admin')->user()->setting->btn_color }};" id="sendBtn" onclick="sendMessage();">Send</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-9 px-0" id="grettings-page">
                    <div>
                        <div class="px-0" id="chat-box-container">
                            <div class="row mx-auto">
                                <div class="d-flex justify-content-center align-items-center flex-column" style="height: 100vh;">
                                    <div>
                                        <button class="btn btn-sm d-lg-none btn-outline-dark px-1 ms-1 toggle"><i class="fas fa-sliders-h" style="font-size: 2.5vh;"></i></button>
                                    </div>
                                    <div class="text-center">
                                        Welcome to the live support center <br> of <a href="{{ Auth::guard('admin')->user()->setting->site_link ?? 'https://softhostit.com/' }}" target="_blank" class="ms-1 text-decoration-none">{{ Auth::guard('admin')->user()->setting->link_title ?? 'Soft Host It' }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('image-modal')
@endsection
@push('js')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
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
        // enter key to send
        $(document).ready(function() {
            $("#attachment").on('click', function() {
                $("#attachmentFile").click();
            });
        });
        // enter key to send

        $("#scrollBottom").on('click', function() {
            setTimeout(function() {
                var scrollDiv = $('#scroll_bottom');
                scrollDiv.scrollTop(scrollDiv.prop('scrollHeight'));
            }, 500);
        });
    </script>

    <script>
        // enter key to send
        $(document).ready(function() {
            $('#message_body').keydown(function(event) {
                if (event.keyCode === 13) { // Check if Enter key is pressed
                    event.preventDefault(); // Prevent default behavior
                    $("#sendBtn").click();
                }
            });
        });
        // enter key to send

        $(document).keyup(function(e) {
            if (e.key === "Escape") { // escape key maps to keycode `27`
                $("#conversation_id").val('');
                $("#total_message").val('');
                $("#grettings-page").removeClass('d-none');
                $("#header-right").addClass('d-none');
                $(".btn-outline-dark").removeClass('active');
            }
        });


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
            var from_url = $('#from_url').val();
            var email = $('#email').val();
            var password = email.split('@')[0];
            if (name.length == 0) {
                $('#name').addClass('border-danger');
                $('#name_Error').text('Name is required!');
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
                    refreshSection();
                    getAllMessages();
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

        // get inboxes
        function getInboxes() {
            fetch("{{ route('admin.get.inboxes') }}")
                .then(response => response.json())
                .then(inboxes => {
                    var data = "";
                    var lastKey = Object.keys(inboxes).sort().reverse()[0];
                    var conversation_id = $("#conversation_id").val();
                    $.each(inboxes, function(key, value) {
                        if (lastKey == key) {
                            var border = 'border-bottom border-0';
                        } else {
                            var border = 'border-0 border-bottom';
                        }
                        if (value.status == 'seen') {
                            var unseen = 'font-weight: normal;';
                        } else {
                            var unseen = 'font-weight: bold; background-color: darkgray;';
                            var sound = "{{ Auth::guard('admin')->user()->sound }}";
                            if (sound == 1) {
                                play();
                            }
                        }
                        var onclick = "onclick = 'viewConversation(" + value.id + ");'"; // inbox onclick attribute
                        var id = "id='inbox" + value.id + "'"; // inbox id attribute
                        if (value.id == conversation_id) {
                            var active = ' active';
                        } else {
                            var active = '';
                        }
                        var classes = "class='p-2 btn btn-outline-dark w-100 shadow-none rounded-0 " + border + active + "'"; // inbox class attribute
                        var style = "style='" + unseen + "'"; // inbox style attribute
                        data = data + "<a href='javascript:;' " + onclick + id + classes + style + ">" + value.name + "</a>"
                    })
                    $('#inboxes').html(data);
                })
                .catch(error => {
                    console.log('Error:', error);
                });
        }
        getInboxes();
        $(document).ready(function() {
            setInterval(getInboxes, 5000);
        });

        var audio;

        function play() {
            audio = new Audio('{{ asset('assets/sound/receive-admin.mp3') }}');
            audio.play();
        }


        // get inboxes

        // refresh the section
        function fetchData() {
            var data_id = $("#conversation_id").val();
            var url = "{{ route('admin.view.conversation', ':id') }}";
            url = url.replace(':id', data_id);
            $.ajax({
                type: "GET",
                dataType: "JSON",
                url: url,
                success: function(messages) {
                    var data = "";
                    $.each(messages[0], function(key, value) {
                        const dateString = value.created_at;
                        const formattedDate = moment.utc(dateString).tz('Asia/Dhaka').format("MMMM DD, YYYY, h:mm A");

                        data = data + "<div class='my-3'>"
                        if (value.type == 'send') {
                            if (value.is_deleted == 1) {
                                data = data + "<div class='d-flex align-items-end justify-content-start agent-message mb-2'>"
                                data = data + "<img src='{{ asset('assets/user.png') }}' width='30' height='30' alt='' class='me-2'>"
                                data = data + "<div class='d-flex w-75 align-items-center'>"

                                data = data + "<span title='" + formattedDate + "' class='px-2 py-1 border-dark' style='border-radius: 10px; !important; background-color: #ebebeb;'>"
                                data = data + "<small style='color: #8b8b8b;'>Message Deleted</small>"
                                data = data + "</span>"

                                data = data + "</div>"
                                data = data + "</div>"
                            } else {
                                data = data + "<div class='d-flex align-items-end justify-content-start agent-message mb-2'>"
                                data = data + "<img src='{{ asset('assets/user.png') }}' width='30' height='30' alt='' class='me-2'>"
                                data = data + "<div class='d-flex w-75 align-items-center'>"
                                if (value.check_image == 1) {
                                    data = data + "<div>"
                                    data = data + "<img width='300' class='rounded border' src='{{ asset('storage/messages/') }}/" + value.content + "' data-bs-toggle='modal' data-bs-target='#imageModal" + value.id + "'>"
                                    data = data + "<br>"
                                    data = data + "<small class='text-success' style='font-size: 10px;'>From: " + value.site + "</small>"
                                    data = data + "<br><small class='text-success' style='font-size: 10px;'> " + formattedDate + "</small>"
                                    data = data + "<small class='text-success' style='font-size: 10px;'> - " + value.user.name + "</small>"
                                    data = data + "</div>"
                                } else {
                                    data = data + "<span class='px-2 py-1 border-dark text-white' style='border-radius: 10px; !important; background-color:#418bd7;'>"
                                    data = data + value.content
                                    data = data + "<br>"
                                    data = data + "<small class='' style='font-size: 10px;'>From: " + value.site + "</small>"
                                    data = data + "<br><small class='' style='font-size: 10px;'> " + formattedDate + "</small>"
                                    if (value.conversation.is_group == 0) {} else {
                                        data = data + " <small>-</small> <small class='' style='font-size: 10px; color: chartreuse;'> " + value.user.name + "</small>"
                                    }
                                    data = data + "</span>"
                                }
                                // reply button
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
                                // reply button

                                data = data + "</div>"
                                data = data + "</div>"
                            }
                        } else {
                            if (value.is_deleted == 1) {
                                data = data + "<div class='d-flex align-items-end justify-content-end clients-message mb-2'>"
                                data = data + "<div class='d-flex w-75 justify-content-end align-items-end'>"

                                data = data + "<span title='" + formattedDate + "' class='px-2 py-1 border-dark' style='border-radius: 10px; !important; background-color: #ebebeb;'>"
                                data = data + "<small style='color: #8b8b8b;'>Message Deleted</small>"
                                data = data + "</span>"

                                data = data + "</div>"
                                data = data + "<img src='{{ asset('assets/user.png') }}' width='30' height='30' alt='' class='ms-2'>"
                                data = data + "</div>"
                            } else {
                                data = data + "<div class='d-flex align-items-end justify-content-end clients-message mb-2'>"
                                data = data + "<div class='d-flex w-75 justify-content-end align-items-end'>"

                                // reply button
                                data = data + '<ul class="navbar-nav me-4">'
                                data = data + '<li class="nav-item dropdown dropend">'
                                data = data + '<a id="navbarDropdown" class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>'
                                data = data + '<i class="fas fa-ellipsis-v" style="font-size: 20px;"></i>'
                                data = data + '</a>'

                                data = data + '<div class="dropdown-menu dropdown-menu-end py-0 rounded-0 ms-3" aria-labelledby="navbarDropdown">'
                                data = data + '<a href="javascript:;" onclick="deleteMessage(' + value.id + ')" class="btn btn-sm btn-block text-start ps-3 w-100 rounded-0">Delete</a>'
                                // @if (Auth::guard('admin')->check())
                                // @endif
                                data = data + '</div>'
                                data = data + '</li>'
                                data = data + '</ul>'
                                // reply button

                                if (value.check_image == 1) {
                                    data = data + "<div class='text-end'>"
                                    data = data + "<img width='300' class='rounded border' src='{{ asset('storage/messages/') }}/" + value.content + "' data-bs-toggle='modal' data-bs-target='#imageModal" + value.id + "'>"
                                    data = data + "<br>"
                                    data = data + "<small class='text-success' style='font-size: 10px;'> " + formattedDate + "</small>"
                                    data = data + "<small class='text-success' style='font-size: 10px;'> - " + value.user.name + "</small>"
                                    data = data + "</div>"
                                } else {
                                    data = data + "<span class='px-2 py-1 border-dark text-end' style='border-radius: 10px; !important; background-color:#ececec;'>"
                                    data = data + value.content
                                    data = data + "<br>"
                                    data = data + "<small class='text-success' style='font-size: 10px;'> " + formattedDate + "</small>"
                                    data = data + "</span>"
                                }

                                data = data + "</div>"
                                data = data + "<img src='{{ Auth::guard('admin')->user()->profile_logo ? asset('storage/admin/' . Auth::guard('admin')->user()->profile_logo) : asset('assets/soft-favicon-logo.png') }}' width='30' height='30' alt='' class='ms-2 rounded-circle border'>"
                                data = data + "</div>"
                            }
                        }
                        data = data + "</div>"
                        $("#conversation_id").val(value.conversation_id);

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

                    // get user info for conversation
                    var html = '';
                    html = html + '<li class="nav-item dropdown">'
                    html = html + '<a href="tel:' + messages[1].phone + '" class="btn btn-sm btn-outline-dark px-1 ms-1">'
                    html = html + '<i class="fas fa-phone" style="font-size: 2.5vh;"></i>'
                    html = html + '</a>'
                    html = html + '</li>'
                    $("#conversationsAction").html(html);
                    $("#inboxHolder").html(messages[1].name);
                    $("#userInfo").html(messages[1].email + '<br>' + messages[1].phone);
                    // get user info for conversation

                    var current_total = messages.length;
                    var total_message = $("#total_message").val();
                    $("#total_message").val(current_total);
                }
            });
        }
        $(document).ready(function() {
            var id = $("#conversation_id").val();
            setTimeout(() => {
                console.log('ok');
                fetchData(id);
                $('.modal').modal('hide');
            }, 10000);
        });
        // refresh the section

        // fetching all data using ajax
        function sendEmail(id) {
            var inbox_id = id;
            var email_subject = $("#email_subject").val();
            var email_message = $("#email_message").val();
            var sendEmailRoute = "{{ route('admin.send.email', ':id') }}";
            sendEmailRoute = sendEmailRoute.replace(':id', inbox_id);
            $.ajax({
                type: "GET",
                dataType: "JSON",
                data: {
                    email_subject: email_subject,
                    email_message: email_message
                },
                url: sendEmailRoute,
                success: function(messages) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Email Sent!',
                        text: 'Email has been send successfully!.',
                    });
                    $('.email-modal').modal('hide');
                }
            });
        }

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
                    fetchData();
                }
            });
        }

        function viewConversation(id) {
            var data_id = id;
            var url = "{{ route('admin.view.conversation', ':id') }}";
            url = url.replace(':id', data_id);
            $.ajax({
                type: "GET",
                dataType: "JSON",
                url: url,
                success: function(messages) {
                    $("#total_message").val(messages.length);
                    $("#header-right").removeClass('d-none');
                    $("#grettings-page").addClass('d-none');
                    $("#sidebar").toggleClass('d-none');
                    var conversation_id = $("#conversation_id").val(id);

                    var email = ''
                    email = email + '<a id="navbarDropdown" class="btn btn-sm btn-outline-dark px-1 ms-1" href="javascript:;" data-bs-toggle="modal" data-bs-target="#EmailModal' + data_id + '">'
                    email = email + '<i class="fas fa-envelope" style="font-size: 2.5vh;"></i>'
                    email = email + '</a>'

                    email = email + '<div class="modal email-modal fade" id="EmailModal' + data_id + '" tabindex="-1" aria-labelledby="EmailModalLabel' + data_id + '" aria-hidden="true">'
                    email = email + '<div class="modal-dialog modal-dialog-centered">'
                    email = email + '<div class="modal-content">'
                    email = email + '<div class="modal-header py-2">'
                    email = email + '<h4 class="mb-0">Send Email</h4>'
                    email = email + '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>'
                    email = email + '</div>'
                    email = email + '<div class="modal-body">'
                    email = email + '<label for="email_subject">Subject</label>'
                    email = email + '<div class="input-group">'
                    email = email + '<input type="text" name="email_subject" id="email_subject" class="form-control" placeholder="Type here">'
                    email = email + '</div>'
                    email = email + '<br>'
                    email = email + '<label for="email_message">Body</label>'
                    email = email + '<div class="input-group">'
                    email = email + '<textarea name="message" id="email_message" rows="5" class="form-control rounded-0" placeholder="Type here"></textarea>'
                    email = email + '</div>'
                    email = email + '</div>'
                    email = email + '<div class="modal-footer justify-content-between">'
                    email = email + '<button type="button" class="btn btn-sm btn-dark" data-bs-dismiss="modal">Close</button>'
                    email = email + '<button type="button" id="sendEmail" onclick="sendEmail(' + data_id + ')" class="btn btn-sm btn-success">Send</button>'
                    email = email + '</div>'
                    email = email + '</div>'
                    email = email + '</div>'
                    email = email + '</div>'

                    $("#emailMenuButton").html(email);
                    $("#sidebar").addClass('d-none');
                    fetchData();
                }
            });
            setTimeout(function() {
                var scrollDiv = $('#scroll_bottom');
                scrollDiv.scrollTop(scrollDiv.prop('scrollHeight'));
            }, 500);

            var seenUrl = "{{ route('admin.seen', ':id') }}";
            seenUrl = seenUrl.replace(':id', id);
            $.ajax({
                type: "GET",
                dataType: "JSON",
                url: seenUrl,
                success: function(messages) {
                    var conversation_id = $("#conversation_id").val(messages[0].conversation_id);
                    $("#total_message").val(messages.length);
                    // fetchData();
                }
            });

            var conversation_id = $("#conversation_id").val();
            if (conversation_id == !null) {
                $("#inbox" + conversation_id).addClass('active');
            }
            getInboxes();
        }
        // Function to scroll to the bottom of the container
        $(document).ready(function() {
            setTimeout(function() {
                var scrollDiv = $('#scroll_bottom');
                scrollDiv.scrollTop(scrollDiv.prop('scrollHeight'));
            }, 500);
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

            var url = "{{ route('admin.send.message', ':id') }}";
            url = url.replace(':id', conversation_id);

            // Create a FormData object
            var formData = new FormData();
            formData.append('image', image);
            formData.append('conversation_id', conversation_id);
            formData.append('message_body', message_body);
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            $.ajax({
                type: "POST",
                dataType: "json",
                data: formData, // Use the FormData object as the data
                contentType: false, // Set contentType to false, jQuery will automatically set the appropriate content type
                processData: false, // Prevent jQuery from processing the data
                enctype: 'multipart/form-data', // Set the enctype option
                url: url,
                success: function(success) {
                    var conversation_id = success.conversation_id;
                    viewConversation(conversation_id);
                    clearField();
                    $("#sendBtn").addClass('shadow-none');
                    setTimeout(function() {
                        var scrollDiv = $('#scroll_bottom');
                        scrollDiv.scrollTop(scrollDiv.prop('scrollHeight'));
                    }, 500);

                    var audio = new Audio('{{ asset('assets/sound/sent.mp3') }}');
                    audio.play();
                },
                error: function(error) {
                    var $errors = error.responseJSON.errors;
                }
            })
        }
        // sending data
    </script>
@endpush

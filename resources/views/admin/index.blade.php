@extends('layouts.admin.app', ['pageTitle' => $pageTitle])
@section('content')
    <div class="row justify-content-center mx-auto vh-100 align-items-center">
        <div class="col-md-5 col-12">
            <div class="row">
                <div class="col-md-12 border border-bottom-0">
                    <h4 class="text-center my-3" style="font-family: 'Courgette', cursive; font-weight: bold;">
                        Welcome To <a class="text-decoration-none" href="https://softhostit.com/" target="_blank">Soft Host IT</a> <br> Live Helping Point
                    </h4>
                </div>
                <hr class="m-0">
                <div class="col-3 px-0">
                    <div class="border border-top-0 border-end-0 px-0" style="height: 100%; overflow-y: scroll;" id="inboxes">
                    </div>
                </div>
                <div class="col-9 px-0">
                    <div class="border border-top-0 px-0" id="chat-box-container">
                        <div class="row mx-auto">
                            <div style="height: 70vh; overflow-y: scroll; scroll-behavior: smooth;" id="scroll_bottom">
                                <div class="col-md-12 px-1 my-0" id="message_box">
                                    <div class="d-flex justify-content-center align-items-center" style="height: 100%; width: 100%;">
                                        Welcome to the live support center of <a href="https://softhostit.com/" target="_blank" class="ms-1 text-decoration-none">Soft Host It</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="input-group" id="input_box">
                            <input type="hidden" id="total_message" name="total_message" value="">
                            <input type="hidden" id="conversation_id" name="conversation_id" value="">
                            <input type="text" name="message_body" id="message_body" class="form-control border-bottom-0 border-top border-start border-end border-0" placeholder="Type Here...">
                            <button class="btn btn-primary rounded-0" id="sendBtn" onclick="sendMessage();">Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
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
                    $.each(inboxes, function(key, value) {
                        if (lastKey == key) {
                            var border = 'border-bottom-1';
                        } else {
                            var border = 'border-bottom-0';
                        }
                        if (value.status == 'seen') {
                            var unseen = 'font-weight: normal;';
                        } else {
                            var unseen = 'font-weight: bold; background-color: darkgray;';
                            var audio = new Audio('{{ asset('assets/sound/receive-admin.mp3') }}');
                            audio.play();
                        }
                        var onclick = "onclick = 'viewConversation(" + value.id + ");'"; // inbox onclick attribute
                        var id = "id='inbox" + value.id + "'"; // inbox id attribute
                        var classes = "class='p-2 btn btn-outline-dark w-100 rounded-0 " + border + "'"; // inbox class attribute
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
            setInterval(getInboxes, 3000);
        });

        // get inboxes

        // refresh the section
        function fetchData(id) {
            var data_id = id;
            var url = "{{ route('admin.view.conversation', ':id') }}";
            url = url.replace(':id', data_id);
            fetch(url)
                .then(response => response.json())
                .then(messages => {
                    var data = "";
                    $.each(messages, function(key, value) {
                        data = data + "<div class='my-3'>"
                        if (value.type == 'send') {
                            data = data + "<div class='d-flex align-items-end justify-content-start agent-message mb-2'>"
                            data = data + "<img src='{{ asset('assets/user.png') }}' width='30' alt='' class='me-2'>"
                            data = data + "<span class='px-2 py-1 border-dark' style='border-radius: 10px; width: 80% !important; background-color:#ececec;'>"
                            data = data + value.content
                            data = data + "</span>"
                            data = data + "</div>"
                        } else {
                            data = data + "<div class='d-flex align-items-end justify-content-end clients-message mb-2'>"
                            data = data + "<span class='px-2 py-1 border-dark' style='border-radius: 10px; width: 80% !important; background-color:#ececec;'>"
                            data = data + value.content
                            data = data + "</span>"
                            data = data + "<img src='{{ asset('assets/user.png') }}' width='30' alt='' class='ms-2'>"
                            data = data + "</div>"
                        }
                        data = data + "</div>"
                        $("#conversation_id").val(value.conversation_id);
                    })
                    $('#message_box').html(data);
                    var current_total = messages.length;
                    var total_message = $("#total_message").val();
                    setTimeout(fetchData, 3000);
                    $("#total_message").val(current_total);
                })
                .catch(error => {
                    console.log('Error:', error);
                });
        }
        // refresh the section

        // fetching all data using ajax
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
                    var conversation_id = $("#conversation_id").val(id);
                    fetchData(data_id);
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

            var url = "{{ route('admin.send.message', ':id') }}";
            url = url.replace(':id', conversation_id);
            $.ajax({
                type: "POST",
                dataType: "json",
                data: {
                    conversation_id: conversation_id,
                    message_body: message_body,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
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

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
                    <div class="border border-top-0 border-end-0 px-0" style="height: 100%">
                        @foreach (config('conversations') as $conversation)
                            <a href="javascript:;" onclick="viewConversation({{ $conversation->id }});" id="inbox{{ $conversation->id }}" class="p-2 btn btn-outline-dark {{ $loop->last ? 'border-bottom-1' : ' border-bottom-0' }} w-100 rounded-0">{{ $conversation->name ?? '' }}</a>
                        @endforeach
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

        // refresh the section
        function refreshSection() {
            // Make an AJAX request to fetch the updated content
            fetch('{{ route('chat-box') }}')
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
        function viewConversation(id) {
            // $("#inbox" + id).on('click', function() {
            //     $("#inbox" + id).addClass('active');
            // });

            var data_id = id;
            var url = "{{ route('admin.view.conversation', ':id') }}";
            url = url.replace(':id', data_id);
            $.ajax({
                type: "GET",
                dataType: "JSON",
                url: url,
                success: function(messages) {
                    var conversation_id = $("#conversation_id").val(messages[0].conversation_id);
                    var data = "";
                    $.each(messages, function(key, value) {
                        data = data + "<div class='my-3'>"
                        if (messages[key].type == 'send') {
                            data = data + "<div class='d-flex align-items-end justify-content-start agent-message mb-2'>"
                            data = data + "<img src='{{ asset('assets/user.png') }}' width='30' alt='' class='me-2'>"
                            data = data + "<span class='px-2 py-1 border-dark' style='border-radius: 10px; width: 80% !important; background-color:#ececec;'>"
                            data = data + messages[key].content
                            data = data + "</span>"
                            data = data + "</div>"
                        } else {
                            data = data + "<div class='d-flex align-items-end justify-content-end clients-message mb-2'>"
                            data = data + "<span class='px-2 py-1 border-dark' style='border-radius: 10px; width: 80% !important; background-color:#ececec;'>"
                            data = data + messages[key].content
                            data = data + "</span>"
                            data = data + "<img src='{{ asset('assets/user.png') }}' width='30' alt='' class='ms-2'>"
                            data = data + "</div>"
                        }
                        data = data + "</div>"
                    })
                    $('#message_box').html(data);
                    setTimeout(viewConversation.conversation_id, 1000);
                }
            });
            setTimeout(function() {
                var scrollDiv = $('#scroll_bottom');
                scrollDiv.scrollTop(scrollDiv.prop('scrollHeight'));
            }, 500);
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
                },
                error: function(error) {
                    var $errors = error.responseJSON.errors;
                }
            })
        }
        // sending data
    </script>
@endpush

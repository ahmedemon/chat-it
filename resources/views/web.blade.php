<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat It</title>
    <link rel="stylesheet" href="{{ asset('assets/bootstrap.min.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Courgette&display=swap" rel="stylesheet">
</head>

<body id="body">
    <div class="row justify-content-center mx-auto vh-100 align-items-center">
        <div class="col-md-4">
            @auth
                <div class="container border px-0 {{ auth()->check() ? '' : 'd-none' }}" id="chat-box-container">
                    <div class="row mx-auto">
                        <div class="col-md-12">
                            <h4 class="text-center my-3" style="font-family: 'Courgette', cursive; font-weight: bold;">
                                Welcome To <a class="text-decoration-none" href="https://softhostit.com/" target="_blank">Soft Host IT</a> <br> Live Helping Point
                            </h4>
                        </div>
                        <hr class="m-0">
                        <div class="col-md-12 px-1 my-0" style="height: 70vh; overflow-y: scroll;" id="message_box">
                            <div class="d-flex justify-content-center align-items-center" style="height: 100%; width: 100%;">
                                Welcome to the live support center of <a href="https://softhostit.com/" target="_blank" class="ms-1 text-decoration-none">Soft Host It</a>
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <input type="hidden" id="conversation_id" name="conversation_id" value="">
                        <input type="text" class="form-control border-bottom-0 border-top border-start border-end border-0 shadow" placeholder="Type Here...">
                        <button class="btn btn-primary rounded-0">Send</button>
                    </div>
                </div>
            @else
                <div class="container border rounded-3" id="login-register-container">
                    <div class="row mb-3 mx-auto">
                        <div class="col-md-12">
                            <h4 class="text-center my-3" style="font-family: 'Courgette', cursive; font-weight: bold;">
                                Welcome To <a class="text-decoration-none" href="https://softhostit.com/" target="_blank">Soft Host IT</a>
                            </h4>
                        </div>
                        <div class="col-md-12">
                            <label for="name" class="text-md-end">{{ __('Name') }}</label>
                            <input id="name" type="name" class="form-control mb-3" name="name" value="{{ old('name') }}" required placeholder="Name">
                            <span class="text-danger small" id="name_Error"></span>
                        </div>

                        <div class="col-md-12">
                            <label for="email" class="text-md-end">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="form-control mb-3" name="email" value="{{ old('email') }}" required placeholder="Email address">
                            <span class="text-danger small" id="email_Error"></span>
                        </div>

                        <div class="col-md-12">
                            <label for="phone" class="text-md-end">{{ __('Phone') }}</label>
                            <input id="phone" type="number" class="form-control mb-3" name="phone" value="{{ old('phone') }}" required placeholder="Phone Number">
                            <span class="text-danger small" id="phone_Error"></span>
                        </div>

                        <div class="col-md-12">
                            <input type="hidden" id="from_url" type="from_url" value="{{ Crypt::encrypt($_SERVER['QUERY_STRING']) }}">
                            <button type="submit" class="btn btn-primary w-100 mt-3" onclick="initial();">{{ __('Submit') }}</button>
                        </div>

                    </div>
                </div>
            @endauth
        </div>
    </div>

</body>

</html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
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
                console.log($errors.name);
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

    // Call the refreshSection function at regular intervals (e.g., every 5 seconds)
    // refresh the section

    // fetching all data using ajax
    function getAllMessages() {
        $.ajax({
            type: "GET",
            dataType: "JSON",
            url: "{{ route('user.get.conversation') }}",
            success: function(messages) {
                $("#conversation_id").val(messages[0].id);
                var data = "";
                $.each(messages, function(key, value) {
                    data = data + "<div class='my-3'>"
                    if (messages[key].type == 'send') {
                        data = data + "<div class='d-flex align-items-end justify-content-end clients-message mb-2'>"
                        data = data + "<span class='px-2 py-1 border-dark' style='border-radius: 10px; width: 80% !important; background-color:#ececec;'>"
                        data = data + messages[key].content
                        data = data + "</span>"
                        data = data + "<img src='{{ asset('assets/user.png') }}' width='30' alt='' class='ms-2'>"
                        data = data + "</div>"
                    } else {
                        data = data + "<div class='d-flex align-items-end justify-content-start agent-message mb-2'>"
                        data = data + "<img src='{{ asset('assets/user.png') }}' width='30' alt='' class='me-2'>"
                        data = data + "<span class='px-2 py-1 border-dark' style='border-radius: 10px; width: 80% !important; background-color:#ececec;'>"
                        data = data + messages[key].content
                        data = data + "</span>"
                        data = data + "</div>"
                    }
                    data = data + "</div>"
                })

                $('#message_box').html(data);
                setTimeout(getAllMessages, 5000);
            }
        });
    }
    $(document).ready(function() {
        getAllMessages();
    });
    // fetching all data using ajax
    // data = data + "<tr>"
    // data = data + "<td class='align-middle text-capitalize py-0'>" + ++key + "</td>"
    // data = data + "<td class='align-middle text-capitalize py-0'>" + value.name + "</td>"
    // data = data + "<td class='align-middle text-capitalize py-0 text-center'>"
    // data = data + "<button class='btn btn-sm btn-light me-1 pe-1' onclick='edit(" + value.id + ")'><i class='bx bx-pen'></i></button>"
    // data = data + "<button class='btn btn-sm btn-light pe-1' onclick='destroy(" + value.id + ")'><i class='bx bx-trash'></i></button>"
    // data = data + "</td>"
    // data = data + "</tr>"
</script>

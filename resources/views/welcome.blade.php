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

<body>
    @auth
        <div class="container px-0 {{ auth()->check() ? '' : 'd-none' }}" id="chat-box-container">
            <div class="row mx-auto">
                <div class="col-md-12">
                    <h4 class="text-center my-3" style="font-family: 'Courgette', cursive; font-weight: bold;">
                        Welcome To <a class="text-decoration-none" href="https://softhostit.com/" target="_blank">Soft Host IT</a>
                    </h4>
                </div>
                <hr class="m-0">
                <div class="col-md-12 px-1 my-2">
                    <div class="d-flex align-items-end justify-content-start agent-message mb-3">
                        <img src="{{ asset('assets/user.png') }}" width="30" alt="" class="me-2">
                        <span class="px-2 py-1 border-dark" style="border-radius: 10px; width: 80% !important; background-color:#ececec;">
                            hi Lorem, incidunt dolores quos quo magni minima dolor, consequatur commodi, officia officiis id possimus.
                        </span>
                    </div>
                    <div class="d-flex align-items-end justify-content-end clients-message">
                        <span class="px-2 py-1 border" style="border-radius: 10px; width: 80% !important;">
                            hi Lorem, incidunt dolores quos quo magni minima dolor, consequatur commodi, officia officiis id possimus.
                        </span>
                        <img src="{{ asset('assets/user.png') }}" width="30" alt="" class="ms-2">
                    </div>
                </div>
            </div>
            <div class="input-group">
                <input type="text" class="form-control border-bottom-0 border-top border-0 shadow" style="border-top-left-radius: 0px; border-top-right-radius: 0px;" placeholder="Type Here...">
                <button class="btn btn-primary rounded-0">Send</button>
            </div>
        </div>
    @else
        <div class="container" id="login-register-container">
            <div class="row mb-3 mx-auto">
                <div class="col-md-12">
                    <h4 class="text-center my-3" style="font-family: 'Courgette', cursive; font-weight: bold;">
                        Welcome To <a class="text-decoration-none" href="https://softhostit.com/" target="_blank">Soft Host IT</a>
                    </h4>
                </div>
                <div class="col-md-12">
                    <label for="name" class="text-md-end">{{ __('Name') }}</label>
                    <input id="name" type="text" class="form-control mb-3" name="name" value="{{ old('name') }}" required placeholder="Name">
                    <span class="text-danger small" id="name_Error"></span>
                </div>

                <div class="col-md-12">
                    <label for="email" class="text-md-end">{{ __('Email Address') }}</label>
                    <input id="email" type="email" class="form-control mb-3" name="email" value="{{ old('email') }}" required placeholder="Email address">
                    <span class="text-danger small" id="email_Error"></span>
                </div>

                <div class="col-md-12">
                    <label for="message" class="text-md-end">{{ __('Message') }}</label>
                    <textarea id="message" class="form-control mb-3" name="message" required placeholder="Message"></textarea>
                    <span class="text-danger small" id="message_Error"></span>
                    <button type="submit" class="btn btn-primary w-100 mt-3" onclick="initial();">{{ __('Submit') }}</button>
                </div>

            </div>
        </div>
    @endauth
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
        var email = $('#email').val();
        var message = $('#message').val();
        var password = email.split('@')[0];
        $.ajax({
            type: "GET",
            dataType: "json",
            data: {
                name: name,
                email: email,
                message: message,
                password: password,
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            url: "{{ route('login.or.create') }}",
            success: function(group) {
                // $('#paymentMethodTable').DataTable().ajax.reload();
                $("#chat-box-container").removeClass('d-none');
                $("#login-register-container").addClass('d-none');
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
                if ($errors.message) {
                    $('#message_Error').text($errors.message);
                    $('#message').addClass('border-danger');
                    toastr.error($errors.message);
                }
            }
        })
    }
    // add data using ajax

    // fetching all data using ajax
    // function getAllMessages() {
    //     $.ajax({
    //         type: "GET",
    //         dataType: "JSON",
    //         url: "",
    //         success: function(roles) {
    //             var data = "";
    //             $.each(roles, function(key, value) {
    //                 data = data + "<tr>"
    //                 data = data + "<td class='align-middle text-capitalize py-0'>" + ++key + "</td>"
    //                 data = data + "<td class='align-middle text-capitalize py-0'>" + value.name + "</td>"
    //                 data = data + "<td class='align-middle text-capitalize py-0 text-center'>"
    //                 data = data + "<button class='btn btn-sm btn-light me-1 pe-1' onclick='edit(" + value.id + ")'><i class='bx bx-pen'></i></button>"
    //                 data = data + "<button class='btn btn-sm btn-light pe-1' onclick='destroy(" + value.id + ")'><i class='bx bx-trash'></i></button>"
    //                 data = data + "</td>"
    //                 data = data + "</tr>"
    //             })
    //             $('tbody').html(data);
    //         }
    //     });
    // }
    // getAllMessages();
    // fetching all data using ajax
</script>

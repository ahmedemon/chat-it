<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('assets/bootstrap.min.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Courgette&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
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
                <label for="message" class="text-md-end">{{ __('Message') }}</label>
                <textarea id="message" class="form-control mb-3" name="message" required placeholder="Message"></textarea>
                <span class="text-danger small" id="message_Error"></span>
                <button type="submit" class="btn btn-primary w-100 mt-3" onclick="initial();">{{ __('Submit') }}</button>
            </div>

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
        var email = $('#email').val();
        var message = $('#message').val();
        $.ajax({
            type: "POST",
            dataType: "json",
            data: {
                name: name,
                email: email,
                message: message,
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            url: "{{ route('login.or.create') }}",
            success: function(group) {
                // $('#paymentMethodTable').DataTable().ajax.reload();
                // Swal.fire({
                //     toast: true,
                //     position: 'top-end',
                //     icon: 'success',
                //     title: 'Bank added successfully!',
                //     showConfirmButton: false,
                //     timer: 1500
                // });
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

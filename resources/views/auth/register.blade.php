<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Spike Free</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/favicon.png') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/styles.min.css') }}" />
    <link href="{{ asset('assets/css/sweetalert/sweetalert2.min.css') }}" rel="stylesheet">
</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <div
            class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-md-10 col-lg-8 col-xxl-3">
                        <div class="card mb-0">
                            <div class="card-body">
                                <h3 class="mb-6 fs-6 fw-bolder">Welcome to Zee CarwashPOS</h3>
                                <p class="text-dark fs-4 mb-7">Create your account</p>
                                <hr />
                                <br />
                                <form>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nama</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            autocomplete="off">
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            autocomplete="off">
                                    </div>
                                    <div class="mb-4">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password"
                                            autocomplete="off">
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="showPassword">
                                        <label class="form-check-label" for="showPassword">
                                            Tampilkan Password
                                        </label>
                                    </div>
                                    <br />
                                    <button type="button" class="btn btn-primary w-100 fs-4 mb-4 rounded-2"
                                        id="auth_register">Daftar
                                    </button>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <p class="fs-4 mb-0 fw-bold">Sudah punya akun?</p>
                                        <a class="text-primary fw-bold ms-2" href="{{ url('/') }}">Login</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{asset('assets/js/sweetalert/sweetalert2.all.min.js')}}"></script>
    <script>
        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        let alphaNumOnly = /^[a-zA-Z0-9]*$/;

        $('#showPassword').on('change', function () {
        const passwordInput = $('#password');
        if ($(this).is(':checked')) {
            passwordInput.attr('type', 'text');
        } else {
            passwordInput.attr('type', 'password');
        }
        });

        $('#auth_register').click(function(){

            var email = $('#email').val();
            var password = $('#password').val();
            var name = $('#name').val();

            if(name === null || name === ''){
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Nama wajib diisi",
                });
                return "";
            }

            if(email === null || email === ''){
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Email wajib diisi",
                });
                return "";
            }

            if(password === null || password === ''){
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Password wajib diisi",
                });
                return "";
            }

            if(email && emailRegex.test(email) === false){
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Email tidak benar",
                });
                return "";
            }

            if(password && alphaNumOnly.test(password) === false){
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Password tidak diizinkan",
                });
                return "";
            }
            if(password && password.length < 6){
                Swal.fire({
                    icon: "error" ,
                    title: "Oops..." ,
                    text: "Password Terlalu pendek, setidaknya 6 karakter"
                });
                return "" ;
            }

            if(password && password.length> 8){
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Password Terlalu panjang, maksimal 8 karakter",
                });
                return "";
            }
            $.ajax({
                type: "POST",
                url: "{{url('/auth/register/store')}}",
                dataType: "json",
                data: {
                    _token: "{{ csrf_token() }}",
                    email: email,
                    password : password,
                    name : name,
                    roles: 'Admin'
                },
                beforeSend : function() {
                    Swal.fire({
                    title:'Mengirim permintaan',
                    text: 'Mohon Tunggu...',
                    allowOutsideClick: false,
                        didOpen: function() {
                            Swal.showLoading()
                        }
                    })
                },
                success: function (data) {
                   Swal.fire({
                        icon: "success",
                        title: "Berhasil",
                        text: "Pendaftaran akun berhasil, silahkan login",
                    });
                    clearForm();
                },
                error: function(data){
                  var result = JSON.parse(data.responseText);
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: result.message,
                    });
                }
            });
        });


    function clearForm(){
        var email = $('#email').val('');
        var password = $('#password').val('');
        var name = $('#name').val('');
    }
    </script>
</body>

</html>
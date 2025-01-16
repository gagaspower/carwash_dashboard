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
                    <div class="col-md-8 col-lg-6 col-xxl-3">
                        <div class="card mb-0">
                            <div class="card-body">
                                <a href="#" class="text-nowrap logo-img text-center d-block py-3 w-100">
                                    <img src="{{ asset('assets/images/logos/dark-logo.svg') }}" width="180" alt="">
                                </a>
                                <p class="text-center">Your Social Campaigns</p>
                                <form>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Username</label>
                                        <input type="email" class="form-control" id="email" name="email">
                                    </div>
                                    <div class="mb-4">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password">
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <a class="text-primary fw-bold" href="#">Lupa password ?</a>
                                    </div>
                                    <button type="button" class="btn btn-primary w-100 fs-4 mb-4 rounded-2"
                                        id="auth_login">Sign
                                        In</button>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <p class="fs-4 mb-0 fw-bold">Tidak punya akun?</p>
                                        <a class="text-primary fw-bold ms-2"
                                            href="./authentication-register.html">Daftar</a>
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
        let alphaNumOnly = '/[^a-zA-Z0-9]/g';

        $('#auth_login').click(function(){

            var email = $('#email').val();
            var password = $('#password').val();

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

            if(password && alphaNumOnly.match(password) === false){
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Password tidak diizinkan",
                });
                return "";
            }

            $.ajax({
                type: "POST",
                url: "{{url('/auth/login-proses')}}",
                dataType: "json",
                data: {
                    _token: "{{ csrf_token() }}",
                    email: email,
                    password : password
                },
                beforeSend : function() {
                    Swal.fire({
                    title:'Otensikasi',
                    text: 'Mohon Tunggu...',
                    allowOutsideClick: false,
                        didOpen: function() {
                            Swal.showLoading()
                        }
                    })
                },
                success: function (data) {
                    if(data.status !== false){
                        const urlDestination = "{{ url('/customer') }}";
                        window.location.replace(urlDestination);
                    }
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


    </script>
</body>

</html>
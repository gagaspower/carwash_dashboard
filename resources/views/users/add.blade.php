@extends('welcome')
@section('content')
<div class="row">
    <div class="col-md-6">
        <h5 class="card-title fw-semibold mb-4">Tambah Pengguna</h5>
        <div class="card">
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    <div class="mb-3">
                        <label for="roles" class="form-label">Roles</label>
                        <select id="roles" class="form-select" name="roles">
                            <option value="0">pilih</option>
                            @foreach ($roles as $value => $label)
                            <option value="{{ $value }}">
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="showPassword">
                        <label class="form-check-label" for="showPassword">
                            Tampilkan Password
                        </label>
                    </div>
                    <br />
                    <button type="button" class="btn btn-primary" id="user_save">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
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
    $('#user_save').click(function(){

        const name = $('#name').val();
        const email = $('#email').val();
        const roles = $('#roles').val();
        const password = $('#password').val();

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
        if(roles === '0' || roles === 0){
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Role wajib diisi",
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
                icon: "error",
                title: "Oops...",
                text: "Password Terlalu pendek, setidaknya 6 karakter",
            });
            return "";
        }
        if(password && password.length > 8){
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Password Terlalu panjang, maksimal 8 karakter",
            });
            return "";
        }

        $.ajax({
            type: "POST",
            url: "{{url('/user/add/store')}}",
            data: {
                _token: "{{ csrf_token() }}",
                name: name,
                email : email,
                roles : roles,
                password: password
            },
            beforeSend : function() {
                Swal.fire({
                    title:'Menyimpan',
                    text: 'Mohon Tunggu...',
                    allowOutsideClick: false,
                    didOpen: function() {
                        Swal.showLoading()
                    }
                })
            },
            success: function (data) {
                Swal.fire({
                    title: "Sukses!",
                    text: "Pengguna berhasil disimpan.",
                    icon: "success"
                });
                clearForm();
            },
            error: function(data){
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Something went wrong!",
                });
            }
        });

    });


    function clearForm() {
        $('#name').val('');
        $('#email').val('');
        $('#password').val('');
        $('#roles').val('0');
    }


</script>
@endsection
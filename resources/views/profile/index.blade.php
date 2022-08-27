@extends('home.main')

@section('main')
    <form id="formAccountSettings" method="POST">
        <div class="container-xxl">
            @if (session()->has('message'))
                <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="row mt-3">
                <div class="col">
                    <div class="card mb-4">
                        <h5 class="card-header">Profile Details</h5>
                        <!-- Account -->
                        <div class="card-body">
                            <div class="d-flex align-items-start align-items-sm-center gap-4">
                                <img src="{{ asset('storage/' . $user->image) }}" alt="user-avatar" class="d-block rounded"
                                    height="100" width="100" id="uploadedAvatar">
                                <div class="button-wrapper">
                                    <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                        <span class="d-none d-sm-block">Upload new photo</span>
                                        <i class="bx bx-upload d-block d-sm-none"></i>
                                        <input type="hidden" value="{{ $user->image }}" name="gambarLama">
                                        <input type="file" name="image" id="upload" class="account-file-input"
                                            hidden="" accept="image/png, image/jpeg">
                                    </label>


                                    <p class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                                </div>
                            </div>
                        </div>
                        <hr class="my-0">
                        <div class="card-body">

                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="firstName" class="form-label">Nama Lengkap</label>
                                    <input class="form-control" type="text" id="name" name="name"
                                        value="{{ $user->name }}" autofocus="">
                                    <div class="invalid-feedback error-name">

                                    </div>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input class="form-control" type="text" id="email" name="email"
                                        value="{{ $user->email }}" placeholder="john.doe@example.com">
                                    <div class="invalid-feedback error-email">

                                    </div>

                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="phoneNumber">No. Telephone</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text">ID (+62)</span>
                                        <input type="text" id="telp" name="telp" class="form-control"
                                            placeholder=" 851 56979 830" value="{{ $user->telp }}">
                                        <div class="invalid-feedback error-telp">

                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="address" class="form-label">Alamat</label>
                                    <input type="text" class="form-control" id="address" name="address"
                                        placeholder="Address" value="{{ $user->address }}">
                                    <div class="invalid-feedback error-address">

                                    </div>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="state" class="form-label">Provinsi</label>
                                    <select class="form-control" name="province" id="province">
                                        @if ($user->province == null)
                                            <option selected value="">
                                                Pilih provinsi anda
                                            </option>
                                        @endif
                                        @foreach ($provinces as $province)
                                            @if ($province->province_id == $user->province)
                                                <option selected value="{{ $province->province_id }}">
                                                    {{ $province->province }}
                                                </option>
                                            @else
                                                <option value="{{ $province->province_id }}">{{ $province->province }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback error-province">

                                    </div>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="state" class="form-label">Kota/Kabupaten</label>

                                    <select class="form-control" value="{{ $user->city }}" name="city" id="city"
                                        disabled>
                                        <option value="" selected>Daftar kota/kabupaten...</option>

                                    </select>
                                    <div class="invalid-feedback error-city">

                                    </div>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="zipCode" class="form-label">Kode Pos</label>
                                    <input type="text" class="form-control" id="zip_code" name="zip_code"
                                        placeholder="59154" maxlength="6" value="{{ $user->zip_code }}">
                                    <div class="invalid-feedback error-zip_code">

                                    </div>
                                </div>

                            </div>
                            <div class="mt-2">
                                <button type="submit" class="btn btn-primary me-2 btn-submit">Save changes</button>

                            </div>
                        </div>
                        <!-- /Account -->
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script>
        $(document).ready(function() {

            if ($('#province').val() != '') {
                getCity();
            }

            $(document).on('submit', '#formAccountSettings', function(e) {
                e.preventDefault();
                let form = new FormData(this);
                updateProfile(form);
            });

            function updateProfile(form) {
                $.ajax({
                    url: '/profile',
                    type: 'post',
                    data: form,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('.btn-submit').prop('disabled', true);

                        $('#formAccountSettings .form-control').removeClass('is-invalid');
                    },
                    success: function(res) {

                        swal({
                            title: "Success",
                            text: res.message,
                            icon: "success",
                            button: "Ok",
                        });

                        $('.btn-submit').prop('disabled', false);
                    },
                    error: function(err) {
                        console.log(err);
                        let errors = err.responseJSON.errors;
                        if (errors.name) {
                            $('#name').addClass('is-invalid');
                            $('.error-name').html(errors.name);
                        }
                        if (errors.telp) {
                            $('#telp').addClass('is-invalid');
                            $('.error-telp').html(errors.telp);
                        }
                        if (errors.email) {
                            $('#email').addClass('is-invalid');
                            $('.error-email').html(errors.email);
                        }
                        if (errors.address) {
                            $('#address').addClass('is-invalid');
                            $('.error-address').html(errors.address);
                        }
                        if (errors.province) {
                            $('#province').addClass('is-invalid');
                            $('.error-province').html(errors.province);
                        }
                        if (errors.city) {
                            $('#city').addClass('is-invalid');
                            $('.error-city').html(errors.city);
                        }
                        if (errors.zip_code) {
                            $('#zip_code').addClass('is-invalid');
                            $('.error-zip_code').html(errors.zip_code);
                        }
                        $('.btn-submit').prop('disabled', false);
                    }
                });
            }
            $(document).on('change', '#province', function() {
                getCity();
            })

            function getCity() {
                let province = $('#province').val();
                $.ajax({
                    url: '/city/' + province,
                    beforeSend: function() {
                        let option = document.createElement("option");
                        option.text = "Loading...";
                        $('#city').html(option);
                        $('#city').prop('disabled', true);
                        $('.btn-submit').prop('disabled', true);
                    },
                    success: function(data) {
                        $('#city').html(data);
                        $('#city').prop('disabled', false);
                        $('.btn-submit').prop('disabled', false);
                    },
                    error: function() {
                        let option = document.createElement("option");
                        option.text = "Pilih provinsi terlebih dahulu";
                        $('#city').html(option);
                    }
                })
            }



        });
    </script>
@endsection

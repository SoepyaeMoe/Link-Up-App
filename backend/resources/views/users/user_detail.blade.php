@extends('layout.app')
@section('title', 'User Info')
@section('users-acitve', 'active')
@section('content')
@if (session('reset_password'))
    <div class="alert alert-success alert-dismissible fade show mt-1" role="alert">
        <div class="mb-0"><span>Reset password success. New password: </span><span id="copy_password">{{ session('reset_password') }}</span><i class="fa-regular fa-copy ml-5" id="copy_btn"></i></div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
@error('password')
    <div class="alert alert-danger alert-dismissible fade show mt-1" role="alert">
        <strong>Reset password fail!</strong>{{ ' ' .$message }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
    </div>
@enderror
    <section class="content-header">
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <div class="col-md-3 md:border-righ">
                            <img style="widht: 120px; height: 120px;" class="rounded-pill" src="{{ $user->profile_photo_path ? asset('storage/profile/'.$user->profile_photo_path) : 'https://ui-avatars.com/api/?name='.$user->name}}" alt="">
                            <p class="font-weight-bold ml-3 mt-2 mb-0">{{ $user->name }}</p>
                            {{-- <button class="btn btn-danger btn-sm ml-3">Delete Photo</button> --}}
                        </div>
                        <div class="col-md-9">
                            <div class="ml-md-2">
                                <div class="d-flex">
                                    <div class="font-weight-bold mr-3"><i class="fa-solid fa-envelope mr-1"></i>Email: </div>
                                    <div>{{ $user->email }}</div>
                                </div>
                                <div class="d-flex">
                                    <div class="font-weight-bold mr-3"><i class="fa-solid fa-phone mr-1"></i>Phone: </div>
                                    <div>{{ $user->phone ? $user->phone : 'Not add yet.' }}</div>
                                </div>
                                <div class="d-flex">
                                    <div class="font-weight-bold mr-3"><i class="fa-solid fa-user-check me-1"></i></i>Bio: </div>
                                    <div>{{ $user->bio ? $user->bio : 'Not add yet.' }}</div>
                                </div>
                                <div class="d-flex">
                                    <div class="font-weight-bold mr-3"><i class="fa-solid fa-clock mr-1"></i>Date of birth: </div>
                                    <div>{{ $user->date_of_birth ? $user->date_of_birth : 'Not add yet.' }}</div>
                                </div>
                                <div class="d-flex">
                                    <div class="font-weight-bold mr-3"><i class="fa-solid fa-clock mr-1"></i>Join On: </div>
                                    <div>{{ $user->created_at }}</div>
                                </div>
                            </div>
                            <div class="mt-md-4">
                                <input type="hidden" id="user_id" value="{{ $user->id }}">
                                <button class="btn btn-sm btn-primary shadow" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo" id="editUser"><i class="fa-solid fa-pen mr-1"></i>Edit</button>
                                <button class="btn btn-sm btn-primary shadow" data-toggle="modal" data-target="#resetPassword"><i class="fa-solid fa-key mr-1"></i>Reset Password</button>
                                <button class="btn btn-sm btn-danger shadow" id="deleteUser"><i class="fa-solid fa-trash mr-1"></i>Delete</button>

                                {{-- user profile edit modal box --}}
                                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <form action="{{ url('edit-user-info') }}" method="post">
                                        @csrf
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Edit Profile</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                                <div class="form-group mb-0">
                                                    <label  class="col-form-label">Email:</label>
                                                    <input type="hidden" name="id" value="{{ $user->id }}">
                                                    <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                                                </div>
                                                <div class="form-group mb-0">
                                                    <label for="name" class="col-form-label">Name:</label>
                                                    <input class="form-control" type="text" id="name" name="name" value="{{ $user->name }}" required>
                                                </div>
                                                <div class="form-group mb-0">
                                                    <label for="phone" class="col-form-label">Phone:</label>
                                                    <input class="form-control" type="number" name="phone" id="phone" value="{{ $user->phone }}">
                                                </div>
                                                <div class="form-group mb-0">
                                                    <label for="bio" class="col-form-label">Bio:</label>
                                                    <input class="form-control" name="bio" id="bio" value="{{ $user->bio }}">
                                                </div>
                                                <div class="form-group mb-0">
                                                    <label for="date_of_birth" class="col-form-label">Date of birth:</label>
                                                    <input class="form-control" type="date" name="date_of_birth" id="date_of_birth" value="{{ $user->date_of_birth }}">
                                                </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <input type="submit" class="btn btn-primary" value="Update">
                                        </div>
                                        </div>
                                    </form>
                                    </div>
                                </div>
                                {{-- // user profile edit --}}

                                {{-- reset pasword modal box--}}
                                    <!-- Modal -->
                                <div class="modal fade" id="resetPassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="{{ url('reset-password') }}" method="post">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Reset Password</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group mb-0">
                                                <input type="hidden" name="id" value="{{ $user->id }}">
                                                <label for="password" class="col-form-label">Password:</label>
                                                <input class="form-control" type="password" name="password" id="password">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                        </form>
                                    </div>
                                    </div>
                                </div>
                                {{-- //reset password --}}
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <a href="{{ url('users/'.$user->id) }}" class="btn border @yield('contents-active') position-relative">
                                Contents
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                  {{ $contents->total() }}
                                </span>
                            </a>

                            <a href="{{ url('following/'.$user->id) }}" class="btn border @yield('following-active') position-relative ml-3">
                                Following
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                  {{ $following->total()  }}
                                </span>
                            </a>

                            <a href="{{ url('follower/'.$user->id) }}" class="btn border @yield('follower-active') position-relative ml-3">
                                Follower
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                  {{ $follower->total() }}
                                </span>
                            </a>
                        </div>
                        {{-- contents --}}
                        @yield('contents')
                        {{-- //contents --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
{{-- @section('extra-script') --}}
<script src={{ asset('js/jquery.min.js') }}></script>
<script>
    $(document).ready(function(){
        $('#deleteUser').click(function(){
            $user_id = $('#user_id').val();
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger m-2"
                },
                buttonsStyling: false
                });
                swalWithBootstrapButtons.fire({
                title: "Are you sure?",
                text: "You want to delete this user.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Delete",
                cancelButtonText: "Cancel!",
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                    type: 'get',
                    data: {'id': $user_id},
                    url: "{{ url('delete-user') }}",
                    success: response => {
                        if(response.status == 200){
                            swalWithBootstrapButtons.fire({
                            title: "Removed!",
                            icon: "success"
                            });
                            window.location.href = "{{ url('users') }}";
                        }else{
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "Something went wrong!",
                            });
                        }
                    }
                    });

                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire({
                    title: "Cancelled",
                    text: "Your imaginary file is safe :)",
                    icon: "error"
                    });
                }
                });
        });
        $('#copy_btn').click(function(){
            navigator.clipboard.writeText($('#copy_password').text());
            alert("Copied the text: " + $('#copy_password').text());
        })
    });
</script>
{{-- @endsection --}}

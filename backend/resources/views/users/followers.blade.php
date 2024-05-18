@extends('users.user_detail')
@section('follower-active', 'bg-primary')
@section('contents')
<div class="col-12 mt-3 w-100">
    <table class="table overflow-hidden">
        <thead>
            <tr>
                <th>User Id</th>
                <th></th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Follow On</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($follower as $f)
                <tr>
                    <td class="align-middle">{{ $f->following_user->id }}</td>
                    <td class="align-middle">
                        <img style="width: 50px; height: 50px; border-radius: 50%;" src="{{ $f->following_user->profile_photo_path ? asset('storage/profile/'.$f->following_user->profile_photo_path) : 'https://ui-avatars.com/api/?name='.$f->following_user->name }}" alt="">
                    </td>
                    <td class="align-middle">{{ $f->following_user->name }}</td>
                    <td class="align-middle">{{ $f->following_user->email }}</td>
                    <td class="align-middle">{{ $f->following_user->phone }} </td>
                    <td class="align-middle"> {{ $f->created_at }} </td>
                    <td class="align-middle">
                        <a href="{{ url('users/'.$f->following_user->id) }}" class="bg-primary btn btn-sm"><i class="fa-solid fa-circle-info"></i></a>
                        <button class="bg-danger btn btn-sm remove" id="{{ $f->id }}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="float-right">
        {{ $contents->links() }}
    </div>
</div>
@endsection
@section('extra-script')
<script>
    $(document).ready(function(){
        $('.remove').click(function(){
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger m-2"
                },
                buttonsStyling: false
                });
                swalWithBootstrapButtons.fire({
                title: "Are you sure?",
                text: "You want to remove this user form follower.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Remove",
                cancelButtonText: "No, cancel!",
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                    type: 'get',
                    data: {'id': this.id},
                    url: "{{ url('remove-follower') }}",
                    success: response => {
                        if(response.status == 200){
                            this.parentElement.parentElement.remove();
                            swalWithBootstrapButtons.fire({
                            title: "Removed!",
                            icon: "success"
                            });
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
    });
</script>
@endsection

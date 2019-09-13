@extends('layouts.app')

@section('content')
@if ($message = Session::get('success'))
<div class="alert alert-success">
  <p>{{ $message }}</p>
</div>
@endif
<div class="row justify-content-xl-center">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-header border-0">
        <div class="row">
          <div class="col-6">
            <h3 class="mb-0">User Management</h3>
          </div>
          {{--<div class="col-6 text-right">
            <a href="{{ route('users.create') }}" class="btn btn-outline-primary" data-toggle="tooltip" data-original-title="Edit product">
              <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
              <span class="btn-inner--text">New User</span>
            </a>
          </div>--}}
        </div>
      </div>
      <div class="table-responsive">
        <table class="table align-items-center table-flush">
          <thead class="thead-light">
            <tr>
              <th>S.No</th>
              <th>User ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Roles</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach ($users as $key => $user)
            <tr>
              <td>{{ ++$i }}</td>
              <td>{{ $user->id }}</td>
              <td>{{ $user->fname }} {{ $user->lname }}</td>
              <td>{{ $user->email }}</td>
              <td>{{ $user->phone }}</td>
              <td>
                @if(!empty($user->roles))
                @foreach($user->roles as $role)
                <label class="label label-success">{{ $role->display_name }}</label>
                @endforeach
                @endif
              </td>
              <td class="table-actions" style="text-align: right;">
                <a class="btn btn-outline-info" href="{{ route('users.show',$user->id) }}">Show</a>
                <a class="btn btn-outline-primary" href="{{ route('users.edit',$user->id) }}">Edit</a>

                <form action="{{ url('admin/users/'.$user->id) }}" method="POST" style="display: inline-block">
                  {{ csrf_field() }}
                  {{ method_field('DELETE') }}
                  <button type="submit" id="delete-task-{{ $user->id }}" class="btn btn-outline-danger"> Delete </button>
                </form>
              </td>
            </tr>
            @endforeach
          </tbody>
          {{ $users->links() }} 
        </table>
      </div>
    </div>
  </div>
</div>

@endsection
@extends('layouts.app')

@section('content')

@if ($message = Session::get('success'))
  <div class="alert alert-success">
    <p>{{ $message }}</p>
  </div>
@endif
<div class="row justify-content-xl-center">
  <div class="col-xl-8">
    <div class="card">
      <div class="card-header border-0">
        <div class="row">
          <div class="col-6">
            <h3 class="mb-0">Roles Management</h3>
          </div>
          <div class="col-6 text-right">
            <a href="{{ route('roles.create') }}" class="btn btn-outline-primary" data-toggle="tooltip" data-original-title="Edit product">
              <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
              <span class="btn-inner--text">New Role</span>
            </a>
          </div>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table align-items-center table-flush">
          <thead class="thead-light">
            <tr>
              <th>S.No</th>
              <th>Roles</th>
              <th>Description</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach ($roles as $key => $role)        
            <tr>
              <td class="table-user">{{ ++$i }}</td>
              <td>{{ $role->display_name }}</td>
              <td>{{ $role->description }}</td>
              <td class="table-actions" style="text-align: right;">
                <a class="btn btn-outline-info" href="{{ route('roles.show',$role->id) }}">Show</a>
                <a class="btn btn-outline-primary" href="{{ route('roles.edit',$role->id) }}">Edit</a>
                <form action="{{ url('admin/roles/'.$role->id) }}" method="POST" style="display: inline-block">
                  {{ csrf_field() }}
                  {{ method_field('DELETE') }}
                  <button type="submit" id="delete-task-{{ $role->id }}" class="btn btn-outline-danger">Delete</button>
                </form>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
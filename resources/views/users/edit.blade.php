@extends('layouts.app')

@section('content')
<div class="row justify-content-md-center">
  <div class="col-md-5">
    <div class="card-wrapper">
      <div class="card">
        <div class="card-header">
          <h3 class="mb-0">Edit User</h3>
        </div>
        <div class="card-body">
          <form class="form-horizontal needs-validation" role="form" method="POST" action="{{ url('admin/users/'.$user->id) }}" novalidate>
            {{ csrf_field() }}
            {{ method_field('PATCH') }}
            <div class="form-group">
              <label class="form-control-label">Display name</label>
              <input type="text" class="form-control" name="name" value="{{$user->name}}" required autofocus>
              <div class="invalid-feedback">
                Name is Required
              </div>     
              @if ($errors->has('name'))
                <div class="invalid-feedback" style="display: block;">
                  {{ $errors->first('name') }}
                </div>
              @endif
            </div>            
            <div class="form-group">
              <label class="form-control-label">E-Mail</label>
              <input type="email" class="form-control" name="email" value="{{$user->email}}" required autofocus>
              <div class="invalid-feedback">
                Name is Required
              </div>     
              @if ($errors->has('email'))
                <div class="invalid-feedback" style="display: block;">
                  {{ $errors->first('email') }}
                </div>
              @endif
            </div>
            <div class="form-group">
              <label class="form-control-label">Password</label>
              <input type="password" class="form-control" name="password" autofocus>   
              @if ($errors->has('password'))
                <div class="invalid-feedback" style="display: block;">
                  {{ $errors->first('password') }}
                </div>
              @endif
            </div>
            <div class="form-group">
              <label class="form-control-label">Confirm Password</label>
              <input type="password" class="form-control" name="password_confirmation" autofocus>   
              @if ($errors->has('password_confirmation'))
                <div class="invalid-feedback" style="display: block;">
                  {{ $errors->first('password_confirmation') }}
                </div>
              @endif
            </div>
            <div class="form-group">
              <label class="form-control-label">Roles</label>
              <select id="role" name="roles[]" multiple class="form-control" multiple>
                @foreach ($roles as $role)
                    <option value="{{$role->id}}" {{in_array($role->id, $userRoles) ? "selected" : null}}>
                        {{$role->display_name}}
                    </option>
                @endforeach
              </select>

                @if ($errors->has('roles'))
                  <div class="invalid-feedback" style="display: block;">
                  {{ $errors->first('roles') }}
                  </div>
                @endif
            </div>
            <button type="submit" class="btn btn-outline-success"> Update </button>
            <a class="btn btn-outline-warning" href="{{ url('admin/roles') }}"> Cancel </a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

    {{-- <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Edit User</div>

                    <div class="panel-body">
                        <!-- Display Validation Errors -->
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif


                        <form class="form-horizontal" role="form" method="POST"
                              action="{{ url('admin/users/'.$user->id) }}">
                            {{ csrf_field() }}
                            {{ method_field('PATCH') }}

                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">Name</label>

                                <div class="col-md-6">
                                    <input id="display_name" type="text" class="form-control" name="name"
                                           value="{{$user->name}}"
                                           required autofocus>

                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">E-Mail</label>

                                <div class="col-md-6">
                                    <input id="email" type="text" class="form-control" name="email"
                                           value="{{$user->email}}"
                                           required autofocus>

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-4 control-label">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" autofocus>
                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                <label for="password_confirmation" class="col-md-4 control-label">Confirm Password</label>

                                <div class="col-md-6">
                                    <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" autofocus>
                                    @if ($errors->has('password_confirmation'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group{{ $errors->has('roles') ? ' has-error' : '' }}">
                                <label for="roles" class="col-md-4 control-label">Roles</label>

                                <div class="col-md-6">

                                    <select id="role" name="roles[]" multiple>
                                        @foreach ($roles as $role)
                                            <option value="{{$role->id}}" {{in_array($role->id, $userRoles) ? "selected" : null}}>
                                                {{$role->display_name}}
                                            </option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('roles'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('roles') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Update
                                    </button>

                                    <a class="btn btn-link" href="{{ url('admin/roles') }}">
                                        Cancel
                                    </a>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div> --}}
@endsection
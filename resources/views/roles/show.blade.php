@extends('layouts.app')

@section('content')
<div class="row justify-content-xl-center">
  <div class="col-xl-4">
    <div class="card">
      <div class="card-header">
        <h5 class="h3 mb-0">Role : Info</h5>
      </div>
      <div class="card-body p-0">
        <ul class="list-group list-group-flush">
          <li class="checklist-entry list-group-item flex-column align-items-start py-4 px-4">
            <div class="checklist-info">
              <h5 class="checklist-title mb-0">Name</h5>
              <small>{{ $role->display_name }}</small>
            </div>
          </li>
          <li class="checklist-entry list-group-item flex-column align-items-start py-4 px-4">
            <div class="checklist-info">
              <h5 class="checklist-title mb-0">Description</h5>
              <small>{{ $role->description }}</small>
            </div>
          </li>
          <li class="checklist-entry list-group-item flex-column align-items-start py-4 px-4">
            <div class="checklist-info">
              <h5 class="checklist-title mb-0">Permissions</h5><br>
              @if(!empty($permissions))
                @foreach($permissions as $permission)
                <button class="btn btn-primary btn-sm">{{ $permission->display_name }}</button>
                @endforeach
              @endif
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>

@endsection
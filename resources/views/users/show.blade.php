@extends('layouts.app')

@section('content')
<div class="row justify-content-xl-center">
  <div class="col-xl-4">
    <div class="card card-profile">
      <img src="/argon/img/theme/img-1-1000x600.jpg" alt="Image placeholder" class="card-img-top">
      <div class="row justify-content-center">
        <div class="col-lg-3 order-lg-2">
          <div class="card-profile-image">
            <a href="#">
              <img src="/argon/img/theme/team-4.jpg" class="rounded-circle">
            </a>
          </div>
        </div>
      </div>
      <div class="card-body pt-0 mt-6">
        <div class="text-center" style="margin-top: 2rem;">
          <h5 class="h3">
            {{ $user->name }}
          </h5>
          <div class="h5 font-weight-300">
            <i class="ni location_pin mr-2"></i>{{ $user->email }}
          </div>
          <div class="h5 mt-4">
            <i class="ni business_briefcase-24 mr-2"></i>Roles - 
            @if(!empty($user->roles))

            @foreach($user->roles as $role)
            @if (!$loop->first)
            ,
            @endif
            {{ $role->display_name }}
            @endforeach
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
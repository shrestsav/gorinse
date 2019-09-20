<div class="scrollbar-inner">
  <!-- Brand -->
  <div class="sidenav-header d-flex align-items-center">
    <router-link :to="{ name: 'dashboard'}" >
      <a class="navbar-brand" href="{{route('dashboard')}}" :href="''" >
        <img src="{{asset('system')}}/img/company-logo.png" class="navbar-brand-img" alt="...">
      </a>
    </router-link>
    <div class="ml-auto">
      <!-- Sidenav toggler -->
      <div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
        <div class="sidenav-toggler-inner">
          <i class="sidenav-toggler-line"></i>
          <i class="sidenav-toggler-line"></i>
          <i class="sidenav-toggler-line"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="navbar-inner">
    <!-- Collapse -->
    <div class="collapse navbar-collapse" id="sidenav-collapse-main">
      <!-- Nav items -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <router-link :to="{ name: 'dashboard'}" >
            <a class="nav-link"  href="{{url('/')}}" :href="'/'" v-bind:class="{ active: currentPage=='dashboard' }">
              <i class="ni ni-ui-04 text-info"></i>
              <span class="nav-link-text">Dashboard</span>
            </a>
          </router-link>
        </li>
        {{--<li class="nav-item">
          <a class="nav-link" href="#navbar-users" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-users" v-bind:class="{ active: currentMenu=='ordersMenu'}">
            <i class="ni ni-ungroup text-orange"></i>
            <span class="nav-link-text">Users</span>
          </a>
          <div class="collapse" id="navbar-users">
            <ul class="nav nav-sm flex-column">
              <li class="nav-item">
                <router-link :to="{ name: 'createUser'}" >
                  <a href="{{url('/v/users/create')}}" :href="''" class="nav-link" v-bind:class="{ active: currentPage=='createUser' }">Create Users</a>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link :to="{ name: 'users'}" >
                  <a href="{{url('/v/users')}}" :href="''" class="nav-link" v-bind:class="{ active: currentPage=='users' }">Users</a>
                </router-link>
              </li>
            </ul>
          </div>
        </li> --}}
        <li class="nav-item">
          <router-link :to="{ name: 'orders'}" >
            <a class="nav-link" href="{{url('/v/orders')}}" :href="''" v-bind:class="{ active: currentMenu=='ordersMenu' }">
              <i class="ni ni-cart text-info"></i>
              <span class="nav-link-text">Orders</span>
            </a>
          </router-link>
        </li>
{{--         <li class="nav-item">
          <a class="nav-link" href="#navbar-orders" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-orders" v-bind:class="{ active: currentMenu=='ordersMenu'}"> 
            <i class="ni ni-cart text-info"></i>
            <span class="nav-link-text">Orders</span>
          </a>
          <div class="collapse" id="navbar-orders">
            <ul class="nav nav-sm flex-column">
              <li class="nav-item">
                <router-link :to="{ name: 'createOrder'}" >
                  <a href="{{url('/v/orders/create')}}" :href="''" class="nav-link" v-bind:class="{ active: currentPage=='createOrder' }">Create Order</a>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link :to="{ name: 'orders'}" >
                  <a href="{{url('/v/orders')}}" :href="''" class="nav-link" v-bind:class="{ active: currentPage=='orders' }">Orders</a>
                </router-link>
              </li>
            </ul>
          </div>
        </li> --}}
        <li class="nav-item">
          <router-link :to="{ name: 'drivers'}" >
            <a class="nav-link" href="{{url('/v/drivers')}}" :href="''" v-bind:class="{active: currentMenu=='driversMenu'}">
              <i class="ni ni-bus-front-12 text-info"></i>
              <span class="nav-link-text">Drivers</span>
            </a>
          </router-link>
        </li>
        <li class="nav-item">
          <router-link :to="{ name: 'customers'}" >
            <a class="nav-link" href="{{url('/v/customers')}}" :href="''" v-bind:class="{active: currentMenu=='customersMenu'}">
              <i class="ni ni-circle-08 text-info"></i>
              <span class="nav-link-text">Customers</span>
            </a>
          </router-link>
        </li>
        {{-- <li class="nav-item">
          <router-link :to="{ name: 'customers'}" >
            <a class="nav-link" href="{{url('/v/customers')}}" :href="''" v-bind:class="{active: currentMenu=='customersMenu'}">
              <i class="ni ni-circle-08 text-info"></i>
              <span class="nav-link-text">Reports</span>
            </a>
          </router-link>
        </li> --}}
{{--         <li class="nav-item">
          <a class="nav-link" href="#navbar-drivers" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-drivers" v-bind:class="{ active: currentMenu=='driversMenu'}">
            <i class="ni ni-bus-front-12 text-info"></i>
            <span class="nav-link-text">Drivers</span>
          </a>
          <div class="collapse" id="navbar-drivers">
            <ul class="nav nav-sm flex-column">
              <li class="nav-item">
                <router-link :to="{ name: 'createDriver'}" >
                  <a href="{{url('/v/drivers/create')}}" :href="''" class="nav-link" v-bind:class="{ active: currentPage=='createDriver' }">Add Driver</a>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link :to="{ name: 'drivers'}" >
                  <a href="{{url('/v/drivers')}}" :href="''" class="nav-link" v-bind:class="{ active: currentPage=='drivers' }">Drivers</a>
                </router-link>
              </li>
            </ul>
          </div>
        </li> --}}
        <li class="nav-item">
          <a class="nav-link" href="#navbar-config" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-config" v-bind:class="{active: currentMenu=='settingsMenu'}">
            <i class="ni ni-settings text-info"></i>
            <span class="nav-link-text">App Settings</span>
          </a>
          <div class="collapse" id="navbar-config">
            <ul class="nav nav-sm flex-column">
              <li class="nav-item">
                <router-link :to="{ name: 'services'}" >
                  <a href="{{url('/v/services')}}" :href="''" class="nav-link" v-bind:class="{active: currentPage=='services'}">Services</a>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link :to="{ name: 'categories'}" >
                  <a href="{{url('/v/categories')}}" :href="''" class="nav-link" v-bind:class="{active: currentPage=='categories'}">Categories</a>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link :to="{ name: 'items'}" >
                  <a href="{{url('/v/items')}}" :href="''" class="nav-link" v-bind:class="{active: currentPage=='items'}">Items</a>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link :to="{ name: 'appDefaults'}" >
                  <a href="{{url('/v/appDefaults')}}" :href="''" class="nav-link" v-bind:class="{active: currentPage=='appDefaults'}">Other</a>
                </router-link>
              </li>
            </ul>
          </div>
        </li>
      </ul>
    </div>
  </div>
</div>
<template>
  <div class="vue_menus">
<!--     <span v-if="currentMenu=='dashboardMenu'">
      <router-link :to="{ name:'staffs' }" class="btn btn-sm btn-neutral">
        <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
        <span class="btn-inner--text">List Staffs</span>
      </router-link>
      <router-link :to="{ name:'createStaff' }" class="btn btn-sm btn-neutral">
        <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
        <span class="btn-inner--text">Add Staff</span>
      </router-link>
    </span> -->
    <!-- Orders Menus -->
    <span v-if="currentMenu=='ordersMenu'">
      <router-link :to="{ name:'orders' }" class="btn btn-sm btn-neutral">
        <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
        <span class="btn-inner--text">Orders</span>
      </router-link>
      <router-link :to="{ name:'createOrder' }" class="btn btn-sm btn-neutral">
        <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
        <span class="btn-inner--text">Create Order</span>
      </router-link>
    </span>
    
    <!-- Drivers Menus -->
    <span v-if="currentMenu=='driversMenu'">
      <router-link :to="{ name: 'drivers'}" class="btn btn-sm btn-neutral">
        <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
        <span class="btn-inner--text">Drivers</span>
      </router-link>
      <router-link :to="{ name: 'createDriver'}" class="btn btn-sm btn-neutral">
        <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
        <span class="btn-inner--text">Add Driver</span>
      </router-link>
    </span>

    <!-- Settings Menus -->
   <!--  <span v-if="currentMenu=='settingsMenu'">
      <router-link :to="{ name: 'services'}" class="btn btn-sm btn-neutral">
        <span class="btn-inner--icon"><i class="fas fa-list-ul"></i></span>
        <span class="btn-inner--text">Services</span>
      </router-link>
      <router-link :to="{ name: 'createService' }" class="btn btn-sm btn-neutral">
        <span class="btn-inner--icon"><i class="fas fa-plus-circle"></i></span>
        <span class="btn-inner--text">Add Services</span>
      </router-link> 
      <router-link :to="{ name: 'categories' }" class="btn btn-sm btn-neutral">
        <span class="btn-inner--icon"><i class="fas fa-calendar-alt"></i></span>
        <span class="btn-inner--text">Categories</span>
      </router-link>
      <router-link :to="{ name: 'createCategory' }" class="btn btn-sm btn-neutral">
        <span class="btn-inner--icon"><i class="fas fa-calendar-alt"></i></span>
        <span class="btn-inner--text">Add Category</span>
      </router-link>
      <router-link :to="{ name: 'items' }" class="btn btn-sm btn-neutral">
        <span class="btn-inner--icon"><i class="fas fa-calendar-alt"></i></span>
        <span class="btn-inner--text">items</span>
      </router-link>
      <router-link :to="{ name: 'createItem' }" class="btn btn-sm btn-neutral">
        <span class="btn-inner--icon"><i class="fas fa-calendar-alt"></i></span>
        <span class="btn-inner--text">Add Item</span>
      </router-link>
    </span> -->
  </div>
</template>

<script type="text/javascript">
  
  export default{
    data(){
      return{
        errors:{},
        clients:{},
      }
    },
    computed: {
      currentMenu(){
        return this.$store.state.currentMenu;
      }
    },
    mounted(){
      
    },
    methods:{
    }
  }
</script>
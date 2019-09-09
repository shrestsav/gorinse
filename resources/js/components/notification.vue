<template>
  <div>
    <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="ni ni-bell-55"><span class="notification_count" v-if="count">{{count}}</span></i>
    </a>
    <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right py-0 overflow-hidden">
      <!-- Dropdown header -->
      <div class="px-3 py-3">
        <h6 class="text-sm text-muted m-0">You have <strong class="text-primary">{{count}}</strong> notifications.</h6>
      </div>
      <!-- List group -->
      <div class="list-group list-group-flush notifications-window">
        <a href="javascript:;" class="list-group-item list-group-item-action" v-for="item in newNotifications.slice().reverse()"  @click="markAsRead(item.id,item.url)">
          <div class="row align-items-center">
            <div class="col-auto">
              <!-- Avatar -->
              <!-- <img alt="Image placeholder" src="" class="avatar rounded-circle"> -->
            </div>
            <div class="col ml--2">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h4 class="mb-0 text-sm">{{item.notifyType.split("_").join(" ") | capitalize}}</h4>
                </div>
                <div class="text-right text-muted">
                  <small>{{ dateDiff(item.created_at)}}</small>
                </div>
              </div>
              <p class="text-sm mb-0">{{item.message}}</p>
            </div>
          </div>
        </a>
        <a href="javascript:;" class="list-group-item list-group-item-action" v-for="item in notifications" @click="markAsRead(item.id,item.data.url)">
          <div class="row align-items-center">
            <div class="col-auto">
              <!-- Avatar -->
              <!-- <img alt="Image placeholder" src="" class="avatar rounded-circle"> -->
            </div>
            <div class="col ml--2">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h4 class="mb-0 text-sm">{{item.data.notifyType.split("_").join(" ") | capitalize}}</h4>
                </div>
                <div class="text-right text-muted">
                  <small>{{ dateDiff(item.data.created_at)}}</small>
                </div>
              </div>
              <p class="text-sm mb-0">{{item.data.message}}</p>
            </div>
          </div>
        </a>
      </div>
      <!-- View all -->
      <a href="javascript:;" class="dropdown-item text-center text-primary font-weight-bold py-3" @click="markAllAsRead()">Clear all</a>
    </div>
  </div>
</template>

<script type="text/javascript">
  
  export default{
    data(){
      return{
        newNotifications:[],
      }
    },
    computed: {
      count(){
        return this.notifications.length + this.newNotifications.length
      },
      notifications(){
        return this.$store.getters.notifications
      }
    },
    mounted(){
      this.$store.dispatch('getNotifications')
      Echo.private('App.User.' + 1)
      .notification((notification) => {
          // console.log(notification);
          this.$swal(notification.message);
          this.newNotifications.push(notification)
          // if(notification.notifyType=='pending_time_exceeded')
          // {
          //   const active = {
          //     order:'',
          //     page:1,
          //     order_id:'',
          //     status:'Pending',
          //   }
          //   this.$store.dispatch('getOrders',active)
          // }

      });
    },
    methods:{
      dateDiff(date){
        var date = new Date(date+' UTC')
        return this.$moment(date).fromNow() // a
      },
      markAsRead(notificationID,url){
        this.$router.push({ name: 'orderDetails', query:{ orderID:url } });
        axios.get('/markAsRead/'+notificationID)
        .then(response => {
          this.$store.dispatch('getNotifications')
          showNotify('success','Marked as read')
        });
      },
      markAllAsRead(){
        this.$store.dispatch('setAllNotificationsRead').then(()=>{
          this.newNotifications = []
        })
      },
      showAlert(){
        // Use sweetalert2
        this.$swal('Hello Vue world!!!');
      }
    },
    filters: {
      capitalize: function (value) {
        if (!value) return ''
        value = value.toString()
        return value.toUpperCase()
      }
    }
  }
</script>

<style type="text/css" scoped>
  .notifications-window
  {
    max-height: 500px;
    overflow: auto;
  }
</style>
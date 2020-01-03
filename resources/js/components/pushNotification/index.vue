<template>
  <div>
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-8">
            <h3 class="mb-0">Push Notification</h3>
          </div>
          <div class="col-4 text-right">
            <!-- <a href="javascript:;" class="btn btn-sm btn-primary">Go Back</a> -->
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label class="form-control-label">Notify To</label>
              <div class="input-group input-group-merge">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                </div>
                <select class="form-control" v-model="notification.type" :class="{'not-validated':errors.type}">
                  <option selected disabled>Select Drivers / Customers</option>
                  <option value="1">Customers</option>
                  <option value="2">Drivers</option>
                  <option value="3">Drivers & Customers</option>
                </select>
              </div>
              <div class="invalid-feedback" style="display: block;" v-if="errors.type">
                {{errors.type[0]}}
              </div>
            </div>
          </div>
          <div class="col-md-8">
            <div class="form-group">
              <label class="form-control-label">Subject</label>
              <div class="input-group input-group-merge">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-user"></i></span>
                </div>
                <input v-model="notification.subject" :class="{'not-validated':errors.subject}" type="text" class="form-control" placeholder="Enter Subject">
              </div>
              <div class="invalid-feedback" style="display: block;" v-if="errors.subject">
                {{errors.subject[0]}}
              </div>
              <small class="text-light">Remaining <strong class="">{{subjectCharacters}}</strong> more characters.</small>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <label class="form-control-label">Message</label>
              <textarea v-model="notification.message" :class="{'not-validated':errors.message}" class="form-control" rows="3" placeholder="BRIEF DESCRIPTION OF COUPON"></textarea>
              <div class="invalid-feedback" style="display: block;" v-if="errors.message">
                {{errors.message[0]}}
              </div>
              <small class="text-light">Remaining <strong class="">{{messageCharacters}}</strong> more characters.</small>
            </div>
          </div>
        </div>
      </div>
      <div class="card-footer text-center">
        <button type="button" class="btn btn-outline-info" @click="sendPushNotification()">Send</button>
        <button type="button" class="btn btn-outline-danger" @click="reset()">Cancel</button>
      </div>
    </div>
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-8">
            <h3 class="mb-0">Push Notification</h3>
          </div>
          <div class="col-4 text-right">
            <!-- <a href="javascript:;" class="btn btn-sm btn-primary">Go Back</a> -->
          </div>
        </div>
      </div>
      
      <div class="table-responsive">
        <table class="table align-items-center table-flush">
          <thead class="thead-light">
            <tr>
              <th>S.No</th>
              <th>Type</th>
              <th>Subject</th>
              <th>Message</th>
              <th>Received by</th>
              <th>Sent At</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item,key in pushNotifications.data">
              <td>{{key+1}}</td>
              <td>{{type(item.type)}}</td>
              <td>{{item.subject}}</td>
              <td>{{item.message}}</td>
              <td>{{item.sent_to.length}} {{type(item.type)}}</td>
              <td>{{datetime(item.created_at)}}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="card-footer py-4" v-if="pushNotifications.data">
        <pagination :data="pushNotifications" @pagination-change-page="getPushNotification"></pagination>
      </div>
    </div>
  </div>
  
</template>

<script>

  export default{
    data(){
      return{
        notification:{
          type:'',
          message:'',
          subject:''
        },
        pushNotifications:{},
        errors:{}
      }
    },
    created(){
      this.$store.commit('changeCurrentPage', 'pushNotification')
      this.$store.commit('changeCurrentMenu', 'notifyMenu')
    },
    mounted(){
      this.getPushNotification()
    },
    methods:{
      sendPushNotification(){
        axios.post('/pushNotification',this.notification)
        .then((response) => {
          this.errors = {}
          this.reset()
          showNotify('success','Notifications has been sent successfully')
        })
        .catch((error) => {
          this.errors = error.response.data.errors
          for (var prop in error.response.data.errors) {
            showNotify('danger',error.response.data.errors[prop])
          }       
        })
      },
      getPushNotification(page = 1){
        axios.get('/pushNotification?page='+page)
        .then((response) => {
          this.pushNotifications = response.data
        })
      },
      reset(){
        this.notification = {
          type:'',
          message:'',
          subject:''
        }
        this.getPushNotification()
      },
      type(type){
        var ret = 'Not Mentioned'
        if(type==1)
          ret = 'Customers'
        else if(type==2)
          ret = 'Drivers'
        else if(type==3)
          ret = 'Customers and Drivers'
        return ret
      },
      datetime(datetime){
        var date = new Date(datetime+' UTC')
        return this.$moment(String(date)).format('YYYY/MM/DD hh:mm a');
      },
    },
    computed: {
      messageCharacters() {
          var char = this.notification.message ? this.notification.message.length : 0,
              limit = 300;

          return limit - char;
      },
      subjectCharacters() {
          var char = this.notification.subject ? this.notification.subject.length : 0,
              limit = 30;

          return limit - char;
      },
    },

  }

</script>

<template>
  <div class="row">
    <div class="col-lg-12">
      <div class="card-wrapper">
        <div class="card">
          <div class="card-header">
            <h3 class="mb-0">General Settings</h3>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="form-control-label">VAT (%)</label>
                  <div class="input-group input-group-merge">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-user"></i></span>
                    </div>
                    <input class="form-control" type="number" v-model="appDefaults.VAT">
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="form-control-label">Delivery Charge</label>
                  <div class="input-group input-group-merge">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input class="form-control" type="number" v-model="appDefaults.delivery_charge">
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="form-control-label">OTP Expiry Time</label>
                  <div class="input-group input-group-merge">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input class="form-control" type="number" v-model="appDefaults.OTP_expiry">
                  </div>
                </div>
              </div>
            </div>
            <div class="text-center">
              <button class="btn btn-outline-primary pull-right" @click="save('generalSetting')">Save</button>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            <h3 class="mb-0">Support Page Setting</h3>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-4">
                <!-- <label class="form-control-label text-center">Company Logo</label> -->
                <img :src="appDefaults.company_logo_url" class="img-center img-fluid" style="height: 109px;">
                <br>
                <div class="custom-file">
                  <input type="file" class="custom-file-input" lang="en">
                  <label class="custom-file-label">Select Logo</label>
                </div>                
              </div>
              <div class="col-md-8">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-control-label">Company Email</label>
                      <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        </div>
                        <input class="form-control" type="text" v-model="appDefaults.company_email">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-control-label">FAQ Link</label>
                      <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                        <input class="form-control" type="text" v-model="appDefaults.FAQ_link">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-control-label">Online Chat</label>
                      <div class="row">
                        <div class="col-md-5">
                          <div class="input-group input-group-merge">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-clock"></i></span>
                            </div>
                            <input class="form-control" type="text" v-model="appDefaults.online_chat.time">
                          </div>
                        </div>
                        <div class="col-md-7">
                          <div class="input-group input-group-merge">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input class="form-control" type="text" v-model="appDefaults.online_chat.url">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-control-label">Hotline Contact No</label>
                      <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        </div>
                        <input class="form-control" type="text" v-model="appDefaults.hotline_contact">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="text-center">
              <button class="btn btn-outline-primary pull-right" @click="save('supportSetting')">Save</button>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            <h3 class="mb-0">Order Settings</h3>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <label class="form-control-label">Order Active Time</label>
                <div class="form-group" v-for="timerange,key in appDefaults.order_time">
                  <div class="input-group input-group-merge" >
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-clock"></i></span>
                    </div>
                    <input class="form-control" type="text"  v-model="appDefaults.order_time[key]">
                  </div>
                </div>
                <button type="button" class="btn btn-primary btn-sm" @click="addTime()">Add Time</button>
              </div>
              <div class="col-md-6">
                <label class="form-control-label">Driver Predefined Notes</label>
                <div class="form-group" v-for="note,key in appDefaults.driver_notes">
                  <div class="input-group input-group-merge">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input class="form-control" type="text" v-model="appDefaults.driver_notes[key]">
                  </div>
                </div>
                <button type="button" class="btn btn-primary btn-sm" @click="addDriverNotes()">Add Notes</button>
              </div>
            </div>
            <div class="text-center">
              <button class="btn btn-outline-primary pull-right" @click="save('orderSetting')">Save</button>
            </div>
          </div>
        </div>
      </div>
    </div>        
  </div>
</template>


<script>
  export default{
    data(){
      return{
      }
    },
    created(){
      this.$store.commit('changeCurrentPage', 'dashboard')
      this.$store.commit('changeCurrentMenu', 'dashboardMenu')
      this.$store.dispatch('getAppDefaults')
    },
    mounted(){
      
    },
    methods:{
      save(part){
        this.appDefaults.saveType = part
        this.$store.dispatch('updateAppDefaults', this.appDefaults)
      },
      addTime(){
        if(this.appDefaults.order_time[this.appDefaults.order_time.length-1]!="")
          this.appDefaults.order_time.push("")
        else
          this.$swal('First Fill Empty Rows');
      },
      addDriverNotes(){
        if(this.appDefaults.driver_notes[this.appDefaults.driver_notes.length-1]!="")
          this.appDefaults.driver_notes.push("")
        else
          this.$swal('First Fill Empty Rows');
      }
    },
    computed: {
      appDefaults(){
        return this.$store.getters.appDefaults
      }
    },
  }

</script>

<style>
  .mx-datepicker{
    width: unset;
    display: unset;
  }
  .mx-datepicker-popup{
    top: 0 !important;
  }
  .not-validated{
    border-color: #fb6340;
  }
  .form-control .vs__dropdown-toggle {
    border: 0px !important;
  }
  .table td, .table th {
    padding: 0.8rem;
  }
  .nav-pills .nav-item:not(:last-child) {
    padding-right: unset;
  }
  .nav-pills .nav-link {
    border-radius: unset;
  }
  .nav-wrapper{
    padding: unset;
  }
  .status_count{
    padding: 2px 7px;
    border-radius: 17px;
    background: #F44336;
    position: relative;
    left: 25px;
    color: white;
  }
  .notification_count{
    padding: 1px 7px 6px 7px;
    border-radius: 17px;
    background: #7d8a92;
    position: relative;
    top: -7px;
  }
</style>
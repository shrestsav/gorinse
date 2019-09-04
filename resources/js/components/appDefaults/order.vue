<template>
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col">
          <h3 class="mb-0">Order</h3>
        </div>
        <div class="col-auto">
          <button type="button" class="btn btn-primary btn-sm" @click="toggleModule">{{module.icon}}</button>
        </div>
      </div>
    </div>
    <div class="card-body" v-if="module.display">
      <div class="row">
        <div class="col-md-12">
          <label class="form-control-label">Order Active Hours</label>
          <div class="row">
            <div class="col-md-2" v-for="timerange,key in appDefaults.order_time">
              <div class="form-group">
                <div class="input-group input-group-merge" >
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                  </div>
                  <input class="form-control" type="text"  v-model="appDefaults.order_time[key]">
                </div>
              </div>
            </div>
          </div>
          <div class="text-center">
            <button type="button" class="btn btn-primary btn-sm" @click="addTime()">Add Time</button>
          </div>
        </div>
        <br><br><br>
        <div class="col-md-12">
          <label class="form-control-label">Driver Predefined Notes</label>
          <div class="row">
            <div class="col-md-4" v-for="note,key in appDefaults.driver_notes">
              <div class="form-group">
                <div class="input-group input-group-merge">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-sticky-note"></i></span>
                  </div>
                  <input class="form-control" type="text" v-model="appDefaults.driver_notes[key]">
                </div>
              </div>
            </div>
          </div>
          <div class="text-center">
            <button type="button" class="btn btn-primary btn-sm" @click="addDriverNotes()">Add Notes</button>
          </div>
        </div>
      </div>
      <div class="float-right">
        <button class="btn btn-outline-primary" @click="save">Save</button>
      </div>
    </div>
  </div>
</template>

<script>
  import { mapState } from 'vuex'
  export default{
    data(){
      return{
        errors:{},
      }
    },
    created(){
    },
    mounted(){

    },
    methods:{
      save(){
        this.appDefaults.saveType = 'orderSetting'
        
        axios.post('/appDefaults',this.appDefaults)
        .then((response) => {
          showNotify('success','Order Settings Updated')
        })
        .catch((error) => {
          for (var prop in error.response.data.errors) {
            showNotify('danger',error.response.data.errors[prop])
          }  
        })
      },
      addTime(){
        if(this.appDefaults.order_time[this.appDefaults.order_time.length-1]!="")
          this.appDefaults.order_time.push("")
        else
          this.$swal({
            type: 'error',
            title: 'First Fill Empty Rows',
          });
      },
      addDriverNotes(){
        if(this.appDefaults.driver_notes[this.appDefaults.driver_notes.length-1]!="")
          this.appDefaults.driver_notes.push("")
        else
          this.$swal({
            type: 'error',
            title: 'First Fill Empty Rows',
          });
      },
      toggleModule(){
        this.$parent.toggleModule('order')
      }
    },
    computed: {
      module(){
        return this.$parent.modules.order
      },
      ...mapState(['appDefaults'])
    },
  }

</script>
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
          <draggable class="row" v-model="appDefaults.order_time">
            <div class="col-md-2" v-for="timerange,key in appDefaults.order_time" @mouseover="orderTime.hover = true" @mouseleave="orderTime.hover = false">
              <div class="form-group">
                <div class="input-group input-group-merge" >
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                  </div>
                  <input class="form-control" type="text"  v-model="appDefaults.order_time[key]">
                  <div class="input-group-prepend" @click="removeOrderTime(key)" v-if="orderTime.hover">
                    <span class="input-group-text text-red"><i class="fas fa-minus-circle"></i></span>
                  </div>
                </div>
              </div>
            </div>
          </draggable>
          <div class="text-center">
            <button type="button" class="btn btn-primary btn-sm" @click="addTime()">Add Time</button>
          </div>
        </div>
        <br><br><br>
        <div class="col-md-12">
          <label class="form-control-label">Driver Predefined Notes</label>
            <draggable class="row" v-model="appDefaults.driver_notes">
              <div class="col-md-4" v-for="note,key in appDefaults.driver_notes" @mouseover="driverNotes.hover = true" @mouseleave="driverNotes.hover = false">
                <div class="form-group">
                  <div class="input-group input-group-merge">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-sticky-note"></i></span>
                    </div>
                    <input class="form-control" type="text" v-model="appDefaults.driver_notes[key]">
                    <div class="input-group-prepend" @click="removeDriverNote(key)" v-if="driverNotes.hover">
                      <span class="input-group-text text-red"><i class="fas fa-minus-circle"></i></span>
                    </div>
                  </div>
                </div>
              </div>
            </draggable>
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
  import draggable from 'vuedraggable'
  import { mapState } from 'vuex'

  export default{
    components: {
      draggable,
    },
    data(){
      return{
        orderTime:{
          hover: false
        },
        driverNotes:{
          hover: false
        },
        errors:{},
      }
    },
    created(){
    },
    mounted(){

    },
    methods:{
      save(){
        if(this.appDefaults.order_time[this.appDefaults.order_time.length-1]==""){
          this.$swal({
            type: 'error',
            title: 'First Fill Empty Order Time Rows',
          });
        }
        else if(this.appDefaults.driver_notes[this.appDefaults.driver_notes.length-1]==""){
          this.$swal({
            type: 'error',
            title: 'First Fill Empty Driver Notes Rows',
          });
        }
        else{
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
        }  
      },
      addTime(){
        if(this.appDefaults.order_time[this.appDefaults.order_time.length-1]!=""){
          this.appDefaults.order_time.push("")
        }
        else{
          this.$swal({
            type: 'error',
            title: 'First Fill Empty Rows',
          });
        }
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
      },
      removeOrderTime(key){
        this.$swal({
          icon: 'warning',
          title: 'Are You Sure?',
          confirmButtonText: 'Yes, delete it!'
        })
        .then((result) => {
          if (result.value) {
            this.appDefaults.order_time.splice(key,1)
          }
        })
      },
      removeDriverNote(key){
        this.$swal({
          icon: 'warning',
          title: 'Are You Sure?',
          confirmButtonText: 'Yes, delete it!'
        })
        .then((result) => {
          if (result.value) {
            this.appDefaults.driver_notes.splice(key,1)
          }
        })
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

<style type="text/css">
  .input-group-prepend .input-group-text {
      border: 1px solid #dee2e6;
  }
  .input-group-prepend:hover{
    cursor: pointer;
  }
</style>
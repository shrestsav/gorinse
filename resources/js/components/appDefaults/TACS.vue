<template>
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col">
          <h3 class="mb-0">Terms and Conditions</h3>
        </div>
        <div class="col-auto">
          <button type="button" class="btn btn-primary btn-sm" @click="toggleModule">{{module.icon}}</button>
        </div>
      </div>
    </div>
    <div class="card-body" v-if="module.display">
      <div class="accordion" id="termsAndConditions">
        <div class="card" v-for="TAC,index in TACS">
          <div class="col-md-12" v-if="editTACS.status && editTACS.index==index">
            <div class="form-group">
              <div class="input-group input-group-merge">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                </div>
                <input class="form-control" type="text" v-model="TAC.title">
              </div>
            </div>
            <div class="form-group">
              <textarea class="form-control" rows="8" placeholder="CONTENT GOES HERE" v-model="TAC.content"></textarea>
            </div>
            <div class="text-right">
              <button type="button" class="btn btn-success btn-sm" @click="update">Update</button>
              <button type="button" class="btn btn-danger btn-sm" @click="cancelUpdate(index)">Cancel</button>
            </div>
          </div>
          <template v-else>
            <div class="card-header" :id="'heading-'+index" data-toggle="collapse" :data-target="'#collapse-'+index" aria-expanded="false" aria-controls="collapseOne">
              <h5 class="mb-0">{{index+1}}. {{TAC.title}}</h5>
            </div>
            <div :id="'collapse-'+index" class="collapse" :aria-labelledby="'heading-'+index" data-parent="#termsAndConditions">
              <div class="card-body">
                <p>{{TAC.content}}</p>
                <div class="text-right">
                  <button type="button" class="btn btn-info btn-sm" @click="edit(index)">Edit</button>
                  <button type="button" class="btn btn-danger btn-sm" @click="deleteTAC(index)">Delete</button>
                </div>
              </div>
            </div>
          </template>
        </div>
      </div>
      <div class="row" v-if="addTACS">
        <div class="col-md-12" >
          <div class="form-group">
            <div class="input-group input-group-merge">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
              </div>
              <input class="form-control" type="text" v-model="newTACS.title">
            </div>
          </div>
          <div class="form-group">
            <textarea class="form-control" rows="8" placeholder="CONTENT GOES HERE" v-model="newTACS.content"></textarea>
          </div>
        </div>
      </div>
      <div class="text-right">
        <button type="button" class="btn btn-primary btn-sm" @click="addTAC" v-if="!addTACS">Add More</button>
        <button type="button" class="btn btn-info btn-sm" @click="saveTAC" v-if="addTACS">Save</button>
        <button type="button" class="btn btn-danger btn-sm" @click="cancelAdd" v-if="addTACS">Cancel</button>
      </div>
    </div>
  </div>
</template>


<script>

  export default{
    data(){
      return{
        addTACS:false,
        editTACS:{
          index:null,
          status:false
        },
        editBtn:true,
        newTACS:{
          title:'',
          content:''
        },
        errors:{},
      }
    },
    methods:{
      edit(index){
        this.editTACS = {
          index:index,
          status:true
        }
        this.editBtn = false
      },
      cancelUpdate(index){
        this.editTACS = {
          index:null,
          status:false
        }
        this.editBtn = true
        this.$store.dispatch('getAppDefaults')
      },
      update(){
        this.save()
      },
      deleteTAC(index){
        this.$swal({
          title: 'Are you sure?',
          text: "You may not undo this",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.value) {
            this.TACS.splice(index, 1)
            this.save()
          }
        })
      },
      addTAC(){
        this.addTACS = true
      },
      cancelAdd(){
        this.addTACS = false,
        this.newTACS = {
          title:'',
          content:''
        }
      },
      saveTAC(){
        this.TACS.push(this.newTACS)
        this.newTACS = {
            title:'',
            content:''
          }
        this.addTACS = false,
        this.save()
      },
      save(){
        var data = {
          saveType: 'TACS',
          TACS: this.TACS
        }
        axios.post('/appDefaults',data)
        .then((response) => {
          this.editTACS = {
            index:null,
            status:false
          }
          this.editBtn = true
          this.$store.dispatch('getAppDefaults')
          showNotify('success','Saved Successfully')
        })
        .catch((error) => {
          for (var prop in error.response.data.errors) {
            showNotify('danger',error.response.data.errors[prop])
          }  
        })
      },
      toggleModule(){
        this.$parent.toggleModule('TACS')
      }
    },
    computed: {
      module(){
        return this.$parent.modules.TACS
      },
      TACS(){
        return this.$parent.appDefaults.TACS
      }
    },
  }

</script>
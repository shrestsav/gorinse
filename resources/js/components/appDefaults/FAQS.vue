<template>
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col">
          <h3 class="mb-0">Frequently Asked Questions</h3>
        </div>
        <div class="col-auto">
          <button type="button" class="btn btn-primary btn-sm" @click="toggleModule">{{module.icon}}</button>
        </div>
      </div>
    </div>
    <div class="card-body" v-if="module.display">
      <div class="accordion" id="termsAndConditions">
        <div class="card" v-for="FAQ,index in FAQS">
          <div class="col-md-12" v-if="editFAQS.status && editFAQS.index==index">
            <div class="form-group">
              <div class="input-group input-group-merge">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                </div>
                <input class="form-control" type="text" v-model="FAQ.title">
              </div>
            </div>
            <div class="form-group">
              <textarea class="form-control" rows="8" placeholder="CONTENT GOES HERE" v-model="FAQ.content"></textarea>
            </div>
            <div class="text-right">
              <button type="button" class="btn btn-success btn-sm" @click="update">Update</button>
              <button type="button" class="btn btn-danger btn-sm" @click="cancelUpdate(index)">Cancel</button>
            </div>
          </div>
          <template v-else>
            <div class="card-header" :id="'heading-'+index" data-toggle="collapse" :data-target="'#collapse-'+index" aria-expanded="false" aria-controls="collapseOne">
              <h5 class="mb-0">{{index+1}}. {{FAQ.title}}</h5>
            </div>
            <div :id="'collapse-'+index" class="collapse" :aria-labelledby="'heading-'+index" data-parent="#termsAndConditions">
              <div class="card-body">
                <p>{{FAQ.content}}</p>
                <div class="text-right">
                  <button type="button" class="btn btn-info btn-sm" @click="edit(index)">Edit</button>
                  <button type="button" class="btn btn-danger btn-sm" @click="deleteFAQ(index)">Delete</button>
                </div>
              </div>
            </div>
          </template>
        </div>
      </div>
      <div class="row" v-if="addFAQS">
        <div class="col-md-12" >
          <div class="form-group">
            <div class="input-group input-group-merge">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
              </div>
              <input class="form-control" type="text" v-model="newFAQS.title">
            </div>
          </div>
          <div class="form-group">
            <textarea class="form-control" rows="8" placeholder="CONTENT GOES HERE" v-model="newFAQS.content"></textarea>
          </div>
        </div>
      </div>
      <div class="text-right">
        <button type="button" class="btn btn-primary btn-sm" @click="addFAQ" v-if="!addFAQS">Add More</button>
        <button type="button" class="btn btn-info btn-sm" @click="saveFAQ" v-if="addFAQS">Save</button>
        <button type="button" class="btn btn-danger btn-sm" @click="cancelAdd" v-if="addFAQS">Cancel</button>
      </div>
    </div>
  </div>
</template>


<script>

  export default{
    data(){
      return{
        addFAQS:false,
        editFAQS:{
          index:null,
          status:false
        },
        editBtn:true,
        newFAQS:{
          title:'',
          content:''
        },
        errors:{},
      }
    },
    methods:{
      edit(index){
        this.editFAQS = {
          index:index,
          status:true
        }
        this.editBtn = false
      },
      cancelUpdate(index){
        this.editFAQS = {
          index:null,
          status:false
        }
        this.editBtn = true
        this.$store.dispatch('getAppDefaults')
      },
      update(){
        this.save()
      },
      deleteFAQ(index){
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
            this.FAQS.splice(index, 1)
            this.save()
          }
        })
      },
      addFAQ(){
        this.addFAQS = true
      },
      cancelAdd(){
        this.addFAQS = false,
        this.newFAQS = {
          title:'',
          content:''
        }
      },
      saveFAQ(){
        this.FAQS.push(this.newFAQS)
        this.newFAQS = {
            title:'',
            content:''
          }
        this.addFAQS = false,
        this.save()
      },
      save(){
        var data = {
          saveType: 'FAQS',
          FAQS: this.FAQS
        }
        axios.post('/appDefaults',data)
        .then((response) => {
          this.editFAQS = {
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
        this.$parent.toggleModule('FAQS')
      }
    },
    computed: {
      module(){
        return this.$parent.modules.FAQS
      },
      FAQS(){
        return this.$parent.appDefaults.FAQS
      }
    },
  }

</script>
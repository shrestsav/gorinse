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
          <div class="card-header" :id="'heading-'+index" data-toggle="collapse" :data-target="'#collapse-'+index" aria-expanded="false" aria-controls="collapseOne">
            <h5 class="mb-0">{{index+1}}. {{TAC.title}}</h5>
          </div>
          <div :id="'collapse-'+index" class="collapse" :aria-labelledby="'heading-'+index" data-parent="#termsAndConditions">
            <div class="card-body">
            <p>{{TAC.content}}</p>
            </div>
          </div>
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
        <button type="button" class="btn btn-info btn-sm" @click="save" v-if="addTACS">Save</button>
        <button type="button" class="btn btn-danger btn-sm" @click="cancelAdd" v-if="addTACS">Cancel</button>
      </div>
    </div>
  </div>
</template>


<script>

  export default{
    data(){
      return{
        // TACS:[
        //   {
        //     title:"Links To Other Web Sites",
        //     content:"A Links To Other Web Sites clause will inform users that you are not responsible for any third party websites that you link to. This kind of clause will generally inform users that they are responsible for reading and agreeing (or disagreeing) with the Terms and Conditions or Privacy Policies of these third parties."
        //   },
        //   {
        //     title:"Content ",
        //     content:"If your website or mobile app allows users to create content and make that content public to other users, a Content section will inform users that they own the rights to the content they have created. The “Content” clause usually mentions that users must give you (the website or mobile app developer) a license so that you can share this content on your website/mobile app and to make it available to other users."
        //   },
        // ],
        addTACS:false,
        newTACS:{
          title:'',
          content:''
        },
        errors:{},
      }
    },
    created(){
    },
    mounted(){
      this.$store.dispatch('getOffers')
    },
    methods:{
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
      save(){
        this.TACS.saveType = 'TACS'
        this.TACS.push(this.newTACS)
        var data = {
          saveType: 'TACS',
          TACS: this.TACS
        }
        axios.post('/appDefaults',data)
        .then((response) => {
          console.log(response)
          showNotify('success','App Default has been created')
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
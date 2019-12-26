<template>
  <div class="card">
    <div class="card-header">
      <div class="row align-items-center">
        <div class="col-4">
          <h6 class="text-light text-uppercase ls-1 mb-1">REPORT</h6>
          <h5 class="h3 text-black mb-0">Top 10 Customers</h5>
        </div>
        <div class="col-5">
          
        </div>
        <div class="col-3 text-right">
          
        </div>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table align-items-center table-flush">
        <thead class="thead-light">
          <tr>
            <th>S.No.</th>
            <th>Customer ID</th>
            <th>Customer Name</th>
            <th>Amount</th>
          </tr>
        </thead>
        <tbody class="list">
          <tr v-for="(item,index) in topCustomers">
            <td>{{++index}}</td>
            <td>{{item.id}}</td>
            <td>{{item.name}}</td>
            <td>AED {{item.amount}}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>


  export default {
    components: {

    },
    data () {
      return {
        topCustomers:[],
      }
    },
    mounted () {
      this.init()
    },
    methods: {
      init(){
        axios.get('/reports/topCustomers')
        .then((response) => {
          this.topCustomers = response.data
        })
      }
    }
  }
</script>
<template>
  <div class='div_form'>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="inputEmail4">Email</label>
          <input type="email" class="form-control" id="inputEmail4" placeholder="Email">
        </div>
        <div class="form-group col-md-6">
          <label for="inputPassword4">Password</label>
          <input type="password" class="form-control" id="inputPassword4" placeholder="Password">
        </div>
      </div>
      <div class="form-group">
        <label for="inputAddress">Username</label>
        <input type="text" name="username" class="form-control" id="inputAddress" placeholder="1234 Main St">
      </div>
      <div class="form-group">
        <label for="inputAddress2">Address 2</label>
        <input type="text" class="form-control" id="inputAddress2" placeholder="Apartment, studio, or floor">
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="inputCity">City</label>
          <input type="text" class="form-control" id="inputCity">
        </div>
        <div class="form-group col-md-4">
          <label for="inputState">State</label>
          <select id="inputState" class="form-control">
            <option selected>Choose...</option>
            <option>...</option>
          </select>
        </div>
        <div class="form-group col-md-2">
          <label for="inputZip">Zip</label>
          <input type="text" class="form-control" id="inputZip">
        </div>
      </div>
      <div class="form-group">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="gridCheck">
          <label class="form-check-label" for="gridCheck">
            Check me out
          </label>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Sign in</button>

      <button @click="send()" class="btn btn-primary">Submit Data</button>
      
  </div>
</template>

<script>
export default {
  data () {
    return {
      send_data: {
        username: 'data_1',
        first_name: 'data_2',
        middle_name: '',
        last_name: 'data_4',
        admin_role_id: 'data_5',
        status: '',
        password: 'data_6',
      },

      response: {}
    }
  },

  created() {
    //this.send_data._token = this.csrf_token
  },

  methods: {
    send () {

      fetch('../api/admin/register', {
        method: 'post',
        body: JSON.stringify(this.send_data),
        headers: {
          'content-type': 'application/json'
          }
        })
        .then(res => res.json())
        .then(data => {
          console.log(data)
          this.response = data
        })
        .catch(error => error.json())
        .then(data => {
          console.log(data)
        })
    }
  }
}
</script>

<style scoped>
.div_form{
  margin: auto;
  width: 400px;
}

</style>
<template>
  <div>
    <div class="bg" :style="{ backgroundImage: `url(${backgroundUrl})` }">
      <div class="bg_overlay"></div>
    </div>

    <div class="center_form">
      <div class="row">
        
        <div class="col-12 ">
          <h1 style="color: #1165F6"><b>Libra-E</b></h1>
          <hr>
        </div>

        <div class="col-12">
          <form class="dashboard_form">

            <div class="form-group">
              <label class="text-secondary" for="username">Username</label>
              <input type="text" class="form-control bg_inputs" id="username" placeholder="Enter Username">
            </div>
            
            <div class="form-group">
              <label class="text-secondary" for="password">Password</label>
              <input type="password" class="form-control bg_inputs" id="password" placeholder="Password">
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">Submit</button>
            
          </form>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      backgroundUrl: '../images/admin/libra_e_admin.jpg'
    }
  }
}
</script>

<style scoped>

.bg {
  /* Full height */
  height: 100vh; 

  background-color: #3482d2;

  filter: blur(4px);
  -webkit-filter: blur(4px);

  /* Center and scale the image nicely */
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
}

.bg_overlay {
  /* Full height */
  height: 100vh; 

  background-color: #205992;
  opacity: .3;
  /* Center and scale the image nicely */
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;

  z-index: 2;
}

.center_form {
  /* background-color: #ffffff; */
  background-color: #fff200;
  color: black;
  font-weight: bold;
  border-radius: 30px;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 3;
  width: 80%;
  max-width: 370px;
  padding: 40px;
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
  transition: 1s;
}

.center_form:hover {
  box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.4), 0 12px 40px 0 rgba(0, 0, 0, 0.29);
}

.bg_inputs {
  background-color: #e6da00;
}


</style>

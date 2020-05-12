<template>
  <div class="form-row animated fadeIn">
    <div class="form-group col-md-2 text-right p-1">
      <span class="text-info"><b>Student Picture*</b></span>
    </div>

    <div class="form-group col-md-6">
      
      <vue-web-cam height="100"/>
      
      <!--
      <div id="my_camera_2" class="border border-info rounded p-2 m-1" style="height:320px; max-width:320px"></div>
      -->
      <button type="button" class="btn btn-primary" >Take Snapshot</button>
      
    </div>
  </div>
</template>

<script>
import { WebCam } from "vue-web-cam";

export default {
  components: {
    'vue-web-cam': WebCam
  },
  data() {
    return {

  }
}
}
</script>
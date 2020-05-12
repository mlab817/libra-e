<template>
  <div>
    <div class="col-12 my-1">
      <div class="form-row">
        <div class="form-group text-secondary col offset-md-1">
          <div onclick="select_radio('student', 1)" @click="select_user(1)" class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="user_radio" value="1" name="user_radio" class="custom-control-input" checked>
            <label class="custom-control-label" for="user_radio"><b>Student</b></label>
          </div>
          <div onclick="select_radio('coach', 2)" @click="select_user(2)" class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="user_radio2" value="2" name="user_radio" class="custom-control-input">
            <label class="custom-control-label" for="user_radio2"><b>Faculty/Coach</b></label>
          </div>
        </div>
      </div>
    </div>
    
    <div id="col_student" class="col-12">
      <div class="form-row animated fadeIn">
        <div class="form-group col-md-4 text-md-right p-1">
          <span class="text-info"><b>Select Student:</b></span>
        </div>

        <div class="form-group col-md-8">
          <select id="select_student" v-model="select_student" name="select_student" class="form-control text-secondary">
            <option disabled value="">---Select Student---</option>
            <option 
              v-for="student in students" 
              :value="student.user_id" 
              :key="student.user_id"
            >
              {{student.l_name}} {{student.f_name}} {{student.m_name}} | {{student.code}}
            </option>
          </select>
        </div>
      </div>
    </div>

    <div id="col_coach" style="display:none" class="col-12">
      <div class="form-row animated fadeIn">
        <div class="form-group col-md-4 text-md-right p-1">
          <span class="text-info"><b>Select Faculty/Coach:</b></span>
        </div>

        <div class="form-group col-md-8">
          <select id="select_coach" v-model="select_coach" name="select_coach" class="form-control text-secondary">
            <option disabled value="">---Select Staff/Coach---</option>
            <option 
              v-for="coach in coaches" 
              :value="coach.user_id" 
              :key="coach.user_id"
            >
              {{coach.l_name}} {{coach.f_name}} {{coach.m_name}} | {{coach.name}}
            </option>
          </select>
        </div>
      </div>
    </div>

    <div class="col-auto">
      <div class="form-row animated fadeIn">
        <div class="form-group col-auto py-1">
          <span class="text-info"><b>Select Book Accession No*</b></span>
        </div>

        <div class="form-group col-auto">
          <select @click="fetch_accession_calendar()" v-model="selected_no_accession"  name="no_accession" class="form-control text-secondary">
            <option
              v-for="no_accession in no_accessions" 
              :value="no_accession.id" 
              :key="no_accession.id"
            >
            {{no_accession.accession_no}}
            </option>
          </select>
        </div>
      </div>
    </div>

    <div class="col-12">
      <v-calendar 
        :min-date='new Date()'
        :max-date='new Date(2020, 3, 1)'
        :disabled-dates='{ weekdays: [1, 7] }'
        :attributes='attributes' 
        color="red" 
      />

      <br>

      <span class="text-secondary"><b>Select Date:</b></span>

       <bounce-loader 
          v-if="loading_status"
          :loading="loading_status" 
          :color="color" 
          :size="44" 
        />
      
      <v-date-picker
        v-model="date"
        :min-date='new Date()'
        :max-date='new Date(2020, 3, 1)'
        :disabled-dates='{ weekdays: [1, 7] }'
        :attributes='attributes' 
        color="blue"
        :input-props='{
          class: "w-full shadow appearance-none border rounded py-2 px-3 text-gray-700 hover:border-blue-5",
          placeholder: "Reserve Date",
          readonly: true
        }'
        :masks="masks"
      />

      <span v-if="alert_status" class="text-danger">
        <strong class="animated shake">{{ alert }}</strong>
      </span>

      <form v-bind:action="url_form" method="POST">

        <input type="hidden" name="_token" v-bind:value="csrf">
        
        <input type="number" name="accession_id" v-bind:value="accession_id" style="display:none;"/>

        <input type="number" name="user_id" v-bind:value="selected_user_id" style="display:none;"/>
        
        <input type="number" name="accession_no_id" v-bind:value="selected_no_accession" style="display:none;"/>

        <input type="date" name="reserve_date" v-bind:value="reserve_date" style="display:none;"/>

        <br>

        <button type="submit" class="btn btn-primary" @click="get_date" :disabled="get_count" >
          Reserve&nbsp;<i class="fas fa-plus-circle"></i>
        </button>
        
        &nbsp;
        
        <span class="text-secondary">
          <b>Note:</b> 
          The maximum of days a student can borrow a book is 2, Maximum of 3 books per student.
        </span>
      </form>
    </div>    
  </div>
</template>

<script>
import VCalendar from 'v-calendar';
import { BounceLoader } from '@saeris/vue-spinners';

export default {
  props: ['url_form', 'csrf', 'count_accessions', 'accession_id', 'no_accessions', 'students', 'coaches'],

  components: {
    'v_calendar': VCalendar,
    'bounce-loader': BounceLoader
  },

  data() {
    return {
      select_student: '',
      select_coach: '',
      user_radio: 1,
      user_id_student: '',
      user_id_coach: '',
      date: '',
      reserve_date: new Date().toISOString().slice(0,10),
      selected_no_accession: 0,
      no_accession_availability: {},
      disabledDates: [
        //{ start: new Date(2019, 10, 18), span: 3 },
        //{ weekdays: [1, 7] }
      ],
      attributes: [], 
      masks:{
        title: 'MMMM YYYY',
        weekdays: 'W',
        navMonths: 'MMM',
        input: ['YYYY-MM-DD'],
        dayPopover: 'WWW, MMM D, YYYY',
        data: ['YYYY-MM-DD'],
      },
      alert: '',
      alert_status: false,
      loading_status: true,
      color: "#1165F6"
    }
  },

  created() {
    if(this.count_accessions > 0){
      this.no_accessions.shift()
      if(this.count_accessions > 1){
        this.selected_no_accession = this.no_accessions[0].id
        this.fetch_accession_calendar()
      }else{
        this.loading_status = false
      }
    }else{
      this.loading_status = false
    }
  },

  computed:{
    get_count(){
      if(this.no_accessions.length <= 0 || this.count_accessions < 1 ){
        return true
      }else{
        return false
      }
    },

    selected_user_id(){
      if(this.user_radio == 1){
        this.user_id_student = this.select_student
        return this.user_id_student
      }else if(this.user_radio == 2){
        this.user_id_coach = this.select_coach
        return this.user_id_coach
      }
    }
  },
  
  methods: {
    select_user(user){
      this.user_radio = user
    },

    get_date(event){
      this.alert = ""
      this.alert_status = false

      if(this.date == ''){
        event.preventDefault();
        this.alert = "Please Select Date!"
        this.alert_status = true
      }else if(this.user_radio == 1 && this.user_id_student == ''){
        event.preventDefault();
        this.alert = "Please Select Student!"
        this.alert_status = true
      }else if(this.user_radio == 2 && this.user_id_coach == ''){
        event.preventDefault();
        this.alert = "Please Select Coach!"
        this.alert_status = true
      }else{
        const date_add = this.date;
        date_add.setDate(date_add.getDate() + 1);
        this.reserve_date = date_add.toISOString().slice(0,10)
      }
    },

    fetch_accession_calendar(){
      fetch('/api/libra_e/no_accession_availability/' + this.selected_no_accession)
      .then(res => res.json())
      .then(res => {
        if(res != 0){
          
          this.no_accession_availability = res
          
          this.no_accession_availability.forEach( accession => {
            const start_date = accession.start_date
            const due_date = accession.due_date
            
            this.attributes.push(
              {
                 key: 'today',
                 highlight: 'red',
                 popover: {
                   label: 'The Book is un-available in this date!',
                 },
                 dates: [
                   {start: new Date(start_date), end: new Date(due_date)}
                 ],
               }
            )
          });
        }else{
          this.attributes = []
        }
        
        this.loading_status = false
        
      })
      .catch(err => console.log(err));
    }
  }
}
</script>
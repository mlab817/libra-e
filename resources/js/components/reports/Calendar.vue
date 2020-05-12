<template>
  <div>
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
      
      <v-date-picker
        v-model="date"
        :min-date='new Date()'
        :max-date='new Date(2020, 3, 1)'
        :disabled-dates='{ weekdays: [1, 7] }'
        :attributes='attributes' 
        color="red"
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

        <input type="number" name="accession_no_id" v-bind:value="selected_no_accession" style="display:none;"/>

        <input type="date" name="reserve_date" v-bind:value="reserve_date" style="display:none;"/>

        <br>

        <button type="submit" class="btn btn-primary" @click="get_date" :disabled="get_count" >
          Reserve&nbsp;<i class="fas fa-plus-circle"></i>
        </button>
        
        &nbsp;
        
        <span class="text-secondary" v-if="user_type == 1">
          <b>Note:</b> 
          The maximum of days a student can borrow a book is 2, Maximum of 3 books per student.
        </span>

      </form>
    </div>    
  </div>
</template>

<script>
import VCalendar from 'v-calendar';

export default {
  props: ['url_form', 'csrf', 'user_type', 'count_accessions', 'accession_id', 'no_accessions'],

  components: {
    'v_calendar': VCalendar,
  },

  data() {
    return {
      date: '',
      reserve_date: new Date().toISOString().slice(0,10),
      selected_no_accession: '',
      no_accession_availability: {},
      disabledDates: [
        //{ start: new Date(2019, 10, 18), span: 3 },
        //{ weekdays: [1, 7] }
      ],
      attributes: [
        {
          key: 'today',
          highlight: true,
          popover: {
            label: 'The Book is un-available in this date!',
          },
          dates: [
            //{start: new Date(2019, 10, 18), span: 3 }
          ],
        }
      ], 
      masks:{
        title: 'MMMM YYYY',
        weekdays: 'W',
        navMonths: 'MMM',
        input: ['YYYY-MM-DD'],
        dayPopover: 'WWW, MMM D, YYYY',
        data: ['YYYY-MM-DD'],
      },
      alert: '',
      alert_status: false
    }
  },

  created() {
    if(this.count_accessions > 0){
      this.no_accessions.shift()
      this.selected_no_accession = this.no_accessions[0].id
      this.fetch_accession_calendar()
    }
  },

  computed:{
    get_count(){
      if(this.no_accessions.length <= 0 || this.count_accessions < 1 ){
        return true
      }else{
        return false
      }
    }
  },
  
  methods: {
    get_date(event){
      this.alert = ""
      this.alert_status = false

      if(this.date == ''){
         event.preventDefault();
         this.alert = "Please Select Date!"
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
        }
      })
      .catch(err => console.log(err));
    }
  }
}
</script>
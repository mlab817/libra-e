<template>
  <div>
    <div class="col-12">
      <bounce-loader 
        v-if="loading_status"
        :loading="loading_status" 
        :color="color" 
        :size="44" 
      />
      
      <v-calendar 
        :min-date='new Date()'
        :max-date='new Date(2020, 3, 1)'
        :disabled-dates='{ weekdays: [1, 7] }'
        :attributes='attributes' 
      />
    </div>    
  </div>
</template>

<script>
import VCalendar from 'v-calendar';
import { BounceLoader } from '@saeris/vue-spinners';

export default {
  props: ['borrow_id', 'accession_no_id', 'status', 'start_date', 'due_date'],

  components: {
    'v_calendar': VCalendar,
    'bounce-loader': BounceLoader
  },

  data() {
    return {
      no_accession_availability: {},
      attributes: [], 
      masks:{
        title: 'MMMM YYYY',
        weekdays: 'W',
        navMonths: 'MMM',
        input: ['YYYY-MM-DD'],
        dayPopover: 'WWW, MMM D, YYYY',
        data: ['YYYY-MM-DD'],
      },
      label: '',
      color_label: '',
      loading_status: true,
      color: "#1165F6",
      min_date: '',
      max_date: ''
    }
  },

  created() {
    this.fetch_accession_calendar()
    this.do_status()
  },

  computed:{

  },
  
  methods: {
    fetch_accession_calendar(){
      fetch('/api/libra_e/no_accession_availability/' + this.accession_no_id)
      .then(res => res.json())
      .then(res => {
        if(res != 0){
          this.no_accession_availability = res

          this.no_accession_availability.forEach( accession => {
            const start_date = accession.start_date
            const due_date = accession.due_date

            if(accession.id == this.borrow_id){
              if(this.status == 3){
                this.label = "These are the user's borrowing dates!"
                this.color_label = 'blue'
              }else{
                this.label = 'These are the Reserve dates!'
                this.color_label = 'teal'
              }

              this.attributes.push(
                {
                  key: 'today',
                  highlight: this.color_label,
                  popover: {
                    label: this.label,
                  },
                  dates: [
                    {start: new Date(start_date), end: new Date(due_date)}
                  ],
                }
              )
            }else{
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
            }
          })
        }
        
        this.loading_status = false
        
      })
      .catch(err => console.log(err));
    },

    do_status(){
      if(this.status == 1 || this.status == 8){
        this.attributes.push(
          {
            key: 'today',
            highlight: 'teal',
            popover: {
              label: 'These are the requested to be Reserved dates!',
            },
            dates: [
              { start: new Date(this.due_date), span: 3  }
            ],
          },
        )
      }else if(this.status == 4){
        this.attributes.push(
          {
            key: 'today',
            highlight: 'green',
            popover: {
              label: 'Date user Returned the book!',
            },
            dates: [
              new Date(this.start_date)
            ],
          },
        )
      }else if(this.status == 5){
        this.attributes.push(
          {
            key: 'today',
            highlight: 'red',
            popover: {
              label: 'Date user declared the book is Damage/Lost!',
            },
            dates: [
              new Date(this.start_date)
            ],
          },
        )
      }else if(this.status == 9){
        this.attributes.push(
          {
            key: 'today',
            highlight: 'red',
            popover: {
              label: 'Date user Canceled Reservation!',
            },
            dates: [
              new Date(this.start_date)
            ],
          },
        )
      }

      if(this.status == 8){
        this.attributes.push(
          {
            key: 'today',
            highlight: 'red',
            popover: {
              label: 'Date Denied Reservation!',
            },
            dates: [
              new Date(this.start_date)
            ],
          },
        )
      }

      if(this.status == 10){
        this.attributes.push(
          {
            key: 'today',
            highlight: 'blue',
            popover: {
              label: "These are the user's borrowing dates!",
            },
            dates: [
              {start: new Date(this.start_date), end: new Date(this.due_date)}
            ],
          },
        )
      }
    }
  }
}
</script>
<template>
  <div>
    <span class="text-secondary">{{code}}</span>
  </div>
</template>

<script>
export default {
  props: ['url_cutter_table', 'input'],
  data () {
    return {
      table: '',
      code: ''
    }
  },

  created() {
    this.fetchCutterCodes();
  },

  methods: {

    fetchCutterCodes () {
      fetch(this.url_cutter_table).then(res => {
        res.text().then( codes => {
          this.table = codes
          this.code = this.cutterFunc(this.input)
        })
      })
      .catch(err => console.log(err));
    },

    cutterFunc (input) {
      
      let inputtxt = input;
      let original = inputtxt;
      let tblc = this.table.split("\n");
      let cutter = '';

      inputtxt = this.check_letter(inputtxt);
      inputtxt = inputtxt.replace(" ", "");
      inputtxt = inputtxt.trim();
      inputtxt = inputtxt.toLowerCase();
      
      for (let j = 0; j < (tblc.length - 1); j++) {
        if (inputtxt >= tblc[j].slice(4) && inputtxt < tblc[j + 1].slice(4)) {
          if (inputtxt[0] == 'a' || inputtxt[0] == 'e' || inputtxt[0] == 'i' || inputtxt[0] == 'o' || inputtxt[0] == 'u') {
            cutter = inputtxt.slice(0, 2).toUpperCase() + tblc[j].slice(0, 3);
          } else if (inputtxt[0] == 's' && inputtxt[1] != 'c') {
            cutter = inputtxt.slice(0, 2).toUpperCase() + tblc[j].slice(0, 3);
          } else if (inputtxt[0] == 's' && inputtxt[1] == 'c') {
            cutter = inputtxt.slice(0, 3).toUpperCase() + tblc[j].slice(0, 3);
          } else {
            cutter = inputtxt[0].toUpperCase() + tblc[j].slice(0, 3);
          }
          
          cutter = cutter.replace("0", "");
          cutter = cutter.replace("0", "");
          
          break;
          
        }
      }
      
      console.log(cutter)

      return cutter

    },

    check_letter (letter) {
  
      letter = letter.replace("Á", "A");
      letter = letter.replace("É", "E");
      letter = letter.replace("Í", "I");
      letter = letter.replace("Ó", "O");
      letter = letter.replace("Ú", "U");
      letter = letter.replace("Ü", "U");
      letter = letter.replace("á", "A");
      letter = letter.replace("é", "E");
      letter = letter.replace("í", "I");
      letter = letter.replace("ó", "O");
      letter = letter.replace("ú", "U");
      letter = letter.replace("ä", "A");
      letter = letter.replace("Ä", "A");
      letter = letter.replace("ë", "E");
      letter = letter.replace("Ë", "E");
      letter = letter.replace("ï", "I");
      letter = letter.replace("Ï", "I");
      letter = letter.replace("ö", "O");
      letter = letter.replace("Ö", "O");
      letter = letter.replace("ü", "U");
      letter = letter.replace("Ü", "U");
      letter = letter.replace("Ç", "C");
      letter = letter.replace("à", "A");
      letter = letter.replace("À", "A");
      letter = letter.replace("è", "E");
      letter = letter.replace("È", "E");
      letter = letter.replace("ì", "I");
      letter = letter.replace("Ì", "I");
      letter = letter.replace("ò", "O");
      letter = letter.replace("Ò", "O");
      letter = letter.replace("ù", "U");
      letter = letter.replace("Ù", "U");
      letter = letter.replace("â", "A");
      letter = letter.replace("Â", "A");
      letter = letter.replace("ê", "E");
      letter = letter.replace("Ê", "E");
      letter = letter.replace("î", "I");
      letter = letter.replace("Î", "I");
      letter = letter.replace("ô", "O");
      letter = letter.replace("Ô", "O");
      letter = letter.replace("û", "U");
      letter = letter.replace("Û", "U");
      letter = letter.replace("ñ", "NZ");

      return letter;
      
    }
    
  }
}
</script>
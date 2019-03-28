export default{
  template:`
  <div>asdasd
    <div v-for="item in items">
      <h3 style="padding-top:20px;">{{ item.wr_subject }}</h3>
      <div style="padding-top:50px;">
          <p v-html="item.wr_content"></p>
      </div>
    </div>
  </div>
  `,
  data(){
    return{
      items: []
    }
  },
  methods: {
    functionName () {
      var id = this.$route.params.id;

      axios.get('http://temamoa.com/api/detail.php?bo_table=free&id='+id)
      .then((response) => {
        var resData = response.data;
        this.items = resData;
      })
      .catch({

      })
    }
  },
  created(){
    this.functionName();
  }
}

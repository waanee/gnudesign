export default{
  name:'page',
  template:`
  <div>
  pages !
  <ul>
  <li v-for="item in data">
  <router-link :to="{name:'bbsdetail', params:{id: item.wr_id}}">title : {{ item.wr_subject }}</router-link>
  </li>
  </ul>
  </div>
  `,
  data(){
    return{
      data : []
    }
  },
  methods: {
    methodName() {

    }
  },
  created() {
    //do something after creating vue instance
    axios.get('http://temamoa.com/api/list.php?bo_table=free')
    .then((response) => {
      var resData = response.data;
      this.data = resData;
    })
    .catch({

    })
  }
}

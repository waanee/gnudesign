var HeaderComponent = () => import('../components/head.js')
var offcanvas = () => import('../components/offcanvas.js')

export default{
  template:`
  <div>
  <offcanvas></offcanvas>
  <header-component></header-component>
  <div class="content_wrap">
  <router-view/>
  </div>
  </div>
  `,
  components: {
    HeaderComponent, offcanvas
  }
}

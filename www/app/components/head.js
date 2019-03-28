// HEADER COMPONENT

export default{
  name:'HeaderComponent',
  template:`
  <header>
    <div class="container">
    <nav class="uk-navbar-container" uk-navbar>
        <div class="uk-navbar-left">
            <router-link to="/" class="uk-navbar-item uk-logo">THELOGO</router-link>
        </div>
        <div class="uk-navbar-center">
          <ul class="uk-navbar-nav">
              <li>
              <router-link to="/">home</router-link>
              </li>
              <li>
              <router-link to="/bbs">bbs</router-link>
              </li>
              <li>
              <router-link to="/about">about</router-link>
              </li>
          </ul>
        </div>
        <div class="uk-navbar-right">
          <span uk-icon="icon: menu" uk-toggle="target: #offcanvas-overlay"></span>
        </div>
    </nav>
    </div>
  </header>
    `,
  data(){
    return{

    }
  }
};

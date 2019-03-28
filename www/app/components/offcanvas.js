export default{
  template:`
  <div id="offcanvas-overlay" uk-offcanvas="overlay: true">
      <div class="uk-offcanvas-bar">

          <button class="uk-offcanvas-close" type="button" uk-close></button>

          <div class="">
            <ul class="uk-nav-default uk-nav-parent-icon" uk-nav>
                <li class="uk-active"><a href="/bbs/board.php?bo_table=free">Community</a></li>
                <li><router-link to="/">main</router-link></li>
                <li><router-link to="/bbs">bbs</router-link></li>
                <li><router-link to="/about">about</router-link></li>
                <li class="uk-parent">
                    <a href="#">Parent</a>
                    <ul class="uk-nav-sub">
                        <li><a href="#">Sub item</a></li>
                        <li><a href="#">Sub item</a></li>
                    </ul>
                </li>
            </ul>
        </div>

      </div>
  </div>
  `
}

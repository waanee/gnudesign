var MainComponent = () => import('./views/index.js')

var app = new Vue({
    router,
    el: '#app',
    components: {
      MainComponent
    }
});

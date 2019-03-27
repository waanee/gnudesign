var MainPage = () => import('../components/main.js')
var SubPage = () => import('../components/page.js')
var About =()=> import('../views/about.js')

// router path components
var routes = [
    {path:'/', component: MainPage},
    {path:'/bbs/board.php', component: SubPage},
    {path:'/about', component:About}
];

var router = new VueRouter({
    routes
});

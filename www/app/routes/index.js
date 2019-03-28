var MainPage = () => import('../components/main.js')
var SubPage = () => import('../components/page.js')
var About = () => import('../views/about.js')
var Detail = () => import('../views/bbsDetail.js')

// router path components
var routes = [
    {name:'main', path:'/', component: MainPage},
    {name:'bbs', path:'/bbs/', component: SubPage},
    {name:'bbsdetail', path:'/bbs/:id', component: Detail},
    {name:'about', path:'/about', component:About}
];

var router = new VueRouter({
    routes
});

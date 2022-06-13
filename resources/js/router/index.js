import {createRouter, createWebHistory} from "vue-router";

const SubscriberIndex = () => import('../pages/Subscriber')
const SubscriberCreate = () => import('../pages/Subscriber/Create')
const SubscriberEdit = () => import('../pages/Subscriber/Edit')

const routes = [
  {path: '/', name: 'SubscriberIndex', component: SubscriberIndex},
  {path: '/subscribers/create', name: 'SubscriberCreate', component: SubscriberCreate},
  {path: '/subscribers/:id', name: 'SubscriberEdit', component: SubscriberEdit},
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router

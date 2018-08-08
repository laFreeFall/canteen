import Report from './components/Report'
import Calendar from './components/Calendar'
import Pupils from './components/Pupils'
import Dishes from './components/Dishes'
import Templates from './components/Templates'

import store from './store/store'


export const routes = [
    {
        path: '/',
        redirect: {
            name: 'calendar'
        }
    },

    {
        path: '/report/',
        name: 'report-generic',
        redirect: to => {
            return `/report/${store.getters.currentWeek.id}`
        }
    },

    {
        path: '/report/:id',
        component: Report,
        name: 'report',
        props: (route) => ({
            weekId: parseInt(route.params.id)
        })
    },
        // props: true

    {
        path: '/calendar',
        component: Calendar,
        name: 'calendar'
    },

    {
        path: '/pupils',
        component: Pupils,
        name: 'pupils'
    },

    {
        path: '/dishes',
        component: Dishes,
        name: 'dishes'
    },

    {
        path: '/templates',
        component: Templates,
        name: 'templates'
    },

    {
        path: '*',
        redirect: {
            name: 'calendar'
        }
    }
]
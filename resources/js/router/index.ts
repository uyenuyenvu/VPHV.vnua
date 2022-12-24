import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router'
import { store } from "../store"

const routeSchedule: Array<RouteRecordRaw> = [

    {
        path: '/',
        name: 'AcademySchedule',
        component: () => import('../pages/Schedule/Academy.vue')
    },
    {
        path: '/item-department/:id',
        name: 'DepartmentSchedule',
        component: () => import('../pages/Schedule/Department.vue')
    },
    {
        path: '/item-schedule/:id',
        name: 'DetailSchedule',
        component: () => import('../pages/Schedule/Detail.vue')
    },
    {
        path: '/create',
        name: 'ScheduleCreate',
        component: () => import('../pages/Schedule/ScheduleCreate.vue'),
        meta: {isAuthenticated: true},
    }
    ]
const routeAdmin: Array<RouteRecordRaw> = [
    {
        path: '',
        name: 'Home',
        component: () => import('../pages/Home.vue'),
        meta: {isAuthenticated: true},
    },
    {
        path: '/ok/test',
        name: 'test',
        component: () => import('../pages/Register.vue')
    },
    {
        path: 'students',
        children: [
            {
                path: '',
                name: 'StudentIndex',
                component: () => import('../pages/Student/StudentIndex.vue'),
                meta: {isAuthenticated: true},
            },
            {
                path: 'create',
                name: 'StudentCreate',
                component: () => import('../pages/Student/StudentCreate.vue'),
                meta: {isAuthenticated: true},
            },
            {
                path: ':id/edit',
                name: 'StudentUpdate',
                component: () => import('../pages/Student/StudentUpdate.vue'),
                meta: {isAuthenticated: true},
            },
            {
                path: ':id',
                name: 'StudentDetail',
                component: () => import('../pages/Student/StudentDetail.vue'),
                meta: {isAuthenticated: true},
            },
        ],
        meta: {isAuthenticated: true},
    },
    {
        path: 'classes',
        meta: {isAuthenticated: true},
        children: [
            {
                path: '',
                name: 'Classes',
                component: () => import('../pages/Classes/ClassesIndex.vue'),
                meta: {isAuthenticated: true},
            },
            {
                path: 'create',
                name: 'ClassesCreate',
                component: () => import('../pages/Classes/ClassesCreate.vue'),
                meta: {isAuthenticated: true},
            },
            {
                path: ':id/edit',
                name: 'ClassesUpdate',
                component: () => import('../pages/Classes/ClassesCreate.vue'),
                meta: {isAuthenticated: true},
            },
            {
                path: ':id',
                name: 'ClassesDetail',
                component: () => import('../pages/Classes/ClassesDetail.vue'),
                meta: {isAuthenticated: true},
            },
        ]
    },
    {
        path: 'report-student',
        meta: {isAuthenticated: true},
        children: [
            {
                path: '',
                name: 'ReportStudent',
                component: () => import('../pages/ReportStudent/ReportStudentIndex.vue'),
                meta: {isAuthenticated: true},
            },
            {
                path: 'create',
                name: 'ReportStudentCreate',
                component: () => import('../pages/ReportStudent/ReportStudentCreate.vue'),
                meta: {isAuthenticated: true},
            },
            {
                path: 'update/:id',
                name: 'ReportStudentUpdate',
                component: () => import('../pages/ReportStudent/ReportStudentUpdate.vue'),
                meta: {isAuthenticated: true},
            },
            {
                path: ':id',
                name: 'ReportStudentDetail',
                component: () => import('../pages/ReportStudent/ReportStudentDetail.vue'),
                meta: {isAuthenticated: true},
            },
        ]
    },
    {
        path: 'review-list',
        meta: {isAuthenticated: true},
        children: [
            {
                path: '',
                name: 'ReviewListIndex',
                component: () => import('../pages/ReviewList/ReviewListIndex.vue'),
                meta: {isAuthenticated: true},
            },
        ]
    },
    {
        path: 'departments',
        meta: {isAuthenticated: true},
        children: [
            {
                path: '',
                name: 'Department',
                component: () => import('../pages/Department/DepartmentIndex.vue'),
                meta: {isAuthenticated: true},
            },
        ]
    },
    {
        path: 'locations',
        meta: {isAuthenticated: true},
        children: [
            {
                path: '',
                name: 'Location',
                component: () => import('../pages/Location/LocationIndex.vue'),
                meta: {isAuthenticated: true},
            },
        ]
    },
    {
        path: 'users',
        meta: {isAuthenticated: true},
        children: [
            {
                path: '',
                name: 'User',
                component: () => import('../pages/User/UserIndex.vue'),
                meta: {isAuthenticated: true},
            },
            {
                path: ':id/edit',
                name: 'UserUpdate',
                component: () => import('../pages/User/UserCreate.vue'),
                meta: {isAuthenticated: true},
            },
            {
                path: 'create',
                name: 'UserCreate',
                component: () => import('../pages/User/UserCreate.vue'),
                meta: {isAuthenticated: true},
            }
        ]
    },
    {
        path: 'roles',
        meta: {isAuthenticated: true},
        children: [
            {
                path: '',
                name: 'Role',
                component: () => import('../pages/Role/RoleIndex.vue'),
                meta: {isAuthenticated: true},
            },
            {
                path: ':id/edit',
                name: 'RoleUpdate',
                component: () => import('../pages/Role/RoleUpdate.vue'),
                meta: {isAuthenticated: true},
            },
            {
                path: 'create',
                name: 'RoleCreate',
                component: () => import('../pages/Role/RoleCreate.vue'),
                meta: {isAuthenticated: true},
            }
        ]
    },

]

const routes: Array<RouteRecordRaw> = [
    {
        path: '/admin',
        meta: {isAuthenticated: true},
        component: () => import('../layouts/AppLayout.vue'),
        children: routeAdmin,
    },  {
        path: '/schedule',
        meta: {isAuthenticated: false},
        component: () => import('../layouts/AppLayout.vue'),
        children: routeSchedule,
    },
    {
        path: '/student/login',
        name: 'LoginStudent',
        component: () => import('../pages/StudentPage/LoginStudent.vue')
    },
    {
        path: '/admin/login',
        name: 'Login',
        component: () => import('../pages/Login.vue')
    },
    {
        path: '/admin/register',
        name: 'Register',
        component: () => import('../pages/Register.vue')
    },
    {
        path: '/authorize/google/callback',
        name: 'LoginGoogle',
        component: () => import('../pages/LoginGoogle.vue')
    },
    {
        path: '/authorize/azure/callback',
        name: 'LoginMicrosoft',
        component: () => import('../pages/LoginMicrosoft.vue')
    },
    {
        path: '/:catchAll(.*)*',
        name: 'Error404',
        component: () => import('../pages/Errors/Error404.vue'),
    },
    {
        path: '/admin/test',
        name: 'Register',
        component: () => import('../pages/Register.vue')
    },
    {
        path: '/stu/login',
        name: 'StuLogin',
        component: () => import('../pages/SVLogin.vue')
    },
    {
        path: '/stu/view',
        name: 'StuView',
        component: () => import('../pages/TraCuuThongTinSV.vue')
    },
]
const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((to, from, next) => {
    if (to.matched.some((route) => route.meta.isAuthenticated)) {
        if (store.state.auth.isAuthenticated) {
            if (to.name === 'Login') {
                next({name: 'Home'})
            }
            next()
        }
        next({name: 'Login'})
    } else if (to.matched.some((route) => route.meta.isAuthenticatedStudent)) {
        if (store.state.authStudent.isAuthenticated) {
            if (to.name === 'LoginStudent') {
                next({name: 'HomeStudent'})
            }
            next()
        }
        next({name: 'LoginStudent'})
    } else {
        // if (store.state.auth.isAuthenticated) {
        //     if (to.name === 'Login') {
        //         next({name: 'Home'})
        //     }
        // }
        next()
    }
});


// @ts-ignore
export default router

import { createRouter, createWebHistory } from 'vue-router'
import ListeSemestre from '../components/ListeSemestre.vue'
import ListeStudentsBySemester from '../components/ListeStudentsBySemester.vue'

const routes = [
  { path: '/', name: 'Semesters', component: ListeSemestre },
  { path: '/semester/:id/students', name: 'SemesterStudents', component: ListeStudentsBySemester, props: true }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router

import { createRouter, createWebHistory } from 'vue-router'
import ListeSemestre from '../components/ListeSemestre.vue'
import ListeStudentsBySemester from '../components/ListeStudentsBySemester.vue'
import ListeEtu from '../components/ListeEtu.vue'
import DetailEtu from '../components/DetailEtu.vue'

const routes = [
  // { path: '/', name: 'Semesters', component: ListeSemestre },
  { path: '/semester/:id/students', name: 'SemesterStudents', component: ListeStudentsBySemester, props: true },
  { path: '/etudiant/:id', name: 'DetailEtu', component: DetailEtu, props: true },
  { path: '/', name: 'ListeEtu', component: ListeEtu },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router

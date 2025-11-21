import { createRouter, createWebHistory } from 'vue-router'
import ListeSemestre from '../components/ListeSemestre.vue'
import ListeStudentsBySemester from '../components/ListeStudentsBySemester.vue'
import ListeEtu from '../components/ListeEtu.vue'
import DetailEtu from '../components/DetailEtu.vue'
import GradesByYear from '../components/GradesByYear.vue'
import NoteSemester from '@/components/NoteSemester.vue'

const routes = [
  // { path: '/', name: 'Semesters', component: ListeSemestre },
  { path: '/semester/:id/students', name: 'SemesterStudents', component: ListeStudentsBySemester, props: true },
  { path: '/etudiant/:id', name: 'DetailEtu', component: DetailEtu, props: true },
  { path: '/grade/L/:year/:id_student', name: 'GradesByYear', component: GradesByYear, props: true },
  { path: '/notes/semester/:id_semester/student/:id_student', name: 'NoteSemester', component: NoteSemester, props: true },
  { path: '/', name: 'ListeEtu', component: ListeEtu },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router

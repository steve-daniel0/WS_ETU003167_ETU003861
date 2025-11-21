<script>
import { apiFetch } from '../api.js';

export default {
  props: ['id'],
  data() {
    return {
      students: [],
      error: null,
      loading: true
    }
  },
  mounted() {
    const semesterId = this.id || this.$route.params.id;
    apiFetch(`/semester/${semesterId}/students`)
      .then(resp => {
        this.loading = false;
        if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
        return resp.json();
      })
      .then(json => {
        this.students = json.data || [];
      })
      .catch(err => {
        this.error = err.message || String(err);
        this.loading = false;
      });
  }
}
</script>

<template>
  <div>
    <router-link to="/">← Retour aux semestres</router-link>
    <h2>Étudiants du semestre {{ id || $route.params.id }}</h2>

    <div v-if="loading">Chargement...</div>
    <div v-else-if="error" style="color:red">Erreur: {{ error }}</div>

    <ul v-else-if="students.length > 0">
      <li v-for="s in students" :key="s.id">
        <b>{{ s.student_code }}</b> — {{ s.name }} {{ s.first_name }} ({{ s.birth }})
        <router-link :to="`/note/S1/${s.student_code}`">Note S1</router-link>
                <router-link :to="`/note/S1/${s.student_code}`">Note S2</router-link>
                        <router-link :to="`/note/S1/${s.student_code}`">Note S3</router-link>
        <router-link :to="`/note/S4/${s.student_code}`">Note S4</router-link>
      </li>
    </ul>

    <div v-else>Aucun étudiant trouvé pour ce semestre.</div>
  </div>

</template>
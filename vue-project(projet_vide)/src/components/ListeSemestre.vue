<script>
export default {
  data() {
    return {
      semesters: [],
      error: null
    }
  },

  mounted() {
    fetch('http://localhost:8085/semester')
      .then(response => {
        console.log('Semester response status:', response.status);
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then(json => {
        console.log('Semester JSON:', json);
        // si l'API suit le même format que les autres
        this.semesters = json.data || [];
      })
      .catch(error => {
        console.error('Erreur semesters:', error);
        this.error = error.message;
      });
  }

}
</script>

<template>
  <div>
    <h2>Liste des semestres</h2>

    <div v-if="error" style="color: red;">Erreur : {{ error }}</div>

    <ul v-else-if="semesters.length > 0">
      <li v-for="s in semesters" :key="s.id">

        <div style="display:flex; align-items:center; gap:8px;">
          <div>
            <b>ID:</b> {{ s.id }} — <b>Label:</b> {{ s.label }} — <b>Year:</b> {{ s.year }}</div>
          <router-link :to="`/semester/${s.id}/students`">Voir étudiants</router-link>
        </div>
      </li>
    </ul>

    <div v-else>Aucun semestre trouvé</div>
  </div>
</template>

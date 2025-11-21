<script>
export default {
  props: ['id'],
  data() {
    return {
      student: null,
      loading: true,
      error: null
    };
  },
  mounted() {
    const studentId = this.id || this.$route.params.id;
    const tryUrls = [ 
      `http://localhost:8085/students/${studentId}`, 
    ];
    
    const attempt = (i = 0) => {
      if (i >= tryUrls.length) {
        this.loading = false;
        this.error = 'Impossible de récupérer les informations de l\'étudiant';
        return;
      }
      fetch(tryUrls[i])
        .then(res => {
          if (!res.ok) return attempt(i + 1);
          return res.json();
        })
        .then(json => {
          if (!json) return; // moved to next
          this.student = json.data || null;
          this.loading = false;
        })
        .catch(err => {
          this.error = err.message || String(err);
          this.loading = false;
        });
    };

    attempt();
  }
}
</script>

<template>
  <div>
    <router-link to="/">← Retour</router-link>

    <div v-if="loading">Chargement...</div>
    <div v-else-if="error" style="color:red">Erreur : {{ error }}</div>

    <div v-else-if="student && student.Student">
      <h2>{{ student.Student.name }} {{ student.Student.first_name }}</h2>
      <div>Code: {{ student.Student.student_code }} — Né(e) : {{ student.Student.birth }}</div>

      <h3>Résumé des moyennes</h3>
      <table border="1" cellpadding="6">
        <tr><th>Semestre</th><th>Moyenne</th><th>Actions</th></tr>
        <tr>
          <td>S1</td>
          <td>{{ student.s?.['1']?.average_common ?? '—' }}</td>
          <td><router-link :to="`/notes/semester/1/student/${student.Student.id}`">Voir détails S1</router-link></td>
        </tr>
        <tr>
          <td>S2</td>
          <td>{{ student.s?.['2']?.average_common ?? '—' }}</td>
          <td><router-link :to="`/notes/semester/2/student/${student.Student.id}`">Voir détails S2</router-link></td>
        </tr>
        <tr>
          <td>S3</td>
          <td>{{ student.s?.['3']?.average_common ?? '—' }}</td>
          <td><router-link :to="`/notes/semester/3/student/${student.Student.id}`">Voir détails S3</router-link></td>
        </tr>
        <tr>
          <td>S4 (tronc commun)</td>
          <td>{{ student.s?.['4']?.average_common ?? '—' }}</td>
          <td>—</td>
        </tr>
      </table>

      <div v-if="student.s?.['4']?.options && student.s['4'].options.length">
        <h4>Options S4</h4>
        <ul>
          <li v-for="opt in student.s['4'].options" :key="opt.option_id">
            <b>{{ opt.option_name }}</b> — Moyenne: {{ opt.average }} —
            <router-link :to="`/grade/S4/${opt.option_id}/${student.Student.id}`">Voir notes S4</router-link>
          </li>
        </ul>
      </div>

      <p>
        <router-link :to="`/grade/L/1/${student.Student.id}`">Note L1</router-link>
        &nbsp;|&nbsp;
        <router-link :to="`/grade/L/2/${student.Student.id}`">Note L2</router-link>
      </p>
    </div>

    <div v-else>
      Aucun étudiant trouvé
    </div>
  </div>
</template> 
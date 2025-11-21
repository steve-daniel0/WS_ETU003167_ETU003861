<script>
export default {
  props: ['id_option', 'id_student'],
  data() {
    return {
      loading: true,
      error: null,
      data: null
    }
  },
  
  computed: {
    totalCredits() {
      if (!this.data?.grades?.grades) return 0;
      return this.data.grades.grades.reduce((sum, grade) => sum + grade.credit, 0);
    }
  },

  mounted() {
    const opt = this.id_option || this.$route.params.id_option;
    const id = this.id_student || this.$route.params.id_student || this.$route.params.id;
    
    if (!opt || !id) {
      this.error = 'Option ID ou Student ID manquant';
      this.loading = false;
      return;
    }

    fetch(`http://localhost:8085/grade/S4/${encodeURIComponent(opt)}/${encodeURIComponent(id)}`)
      .then(r => {
        this.loading = false;
        if (!r.ok) throw new Error(`HTTP ${r.status}`);
        return r.json();
      })
      .then(json => {
        console.log('JSON reçu:', json);
        this.data = json.data || null;
      })
      .catch(err => {
        this.error = err.message || String(err);
        this.loading = false;
      });
  }
}
</script>

<template>
  <div class="option-grades-container">
    <router-link to="/" class="back-link">← Retour</router-link>

    <div v-if="loading" class="loading">Chargement...</div>

    <div v-else-if="error" class="error-message">
      Erreur : {{ error }}
    </div>

    <div v-else-if="data && data.student" class="content">
      
      <!-- En-tête avec infos étudiant -->
      <div class="header-card">
        <div class="student-info-section">
          <h1>Notes Semestre 4 - Option {{ id_option || $route.params.id_option }}</h1>
          <div class="student-details">
            <div class="detail-item">
              <span class="label">Code étudiant :</span>
              <span class="value">{{ data.student.student_code }}</span>
            </div>
            <div class="detail-item">
              <span class="label">Nom :</span>
              <span class="value">{{ data.student.name }} {{ data.student.first_name }}</span>
            </div>
            <div class="detail-item">
              <span class="label">Date de naissance :</span>
              <span class="value">{{ data.student.birth }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Résumé des résultats -->
      <div class="summary-card">
        <div class="summary-item">
          <span class="summary-label">Nombre de matières</span>
          <span class="summary-value">{{ data.grades.grades.length }}</span>
        </div>
        <div class="summary-item">
          <span class="summary-label">Total crédits</span>
          <span class="summary-value">{{ totalCredits }}</span>
        </div>
        <div class="summary-item highlight">
          <span class="summary-label">Moyenne</span>
          <span class="summary-value">{{ data.grades.average }}/20</span>
        </div>
        <div class="summary-item highlight">
          <span class="summary-label">Mention</span>
          <span class="summary-value mention">{{ data.grades.mention }}</span>
        </div>
      </div>

      <!-- Tableau des notes -->
      <div class="grades-table-container">
        <table class="grades-table">
          <thead>
            <tr>
              <th>Matière</th>
              <th>Crédits</th>
              <th>Note</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(g, index) in data.grades.grades" :key="index">
              <td data-label="Matière">{{ g.subject_name }}</td>
              <td data-label="Crédits">{{ g.credit }}</td>
              <td data-label="Note" :class="{ 'passing': g.grade >= 10, 'failing': g.grade < 10 }">
                <strong>{{ g.grade !== null ? g.grade + '/20' : '—' }}</strong>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>

    <div v-else class="no-data">
      Aucune donnée disponible
    </div>
  </div>
</template>

<script>
import { apiFetch } from '../api.js';

export default {
  props: ['year','id_student'],
  data() {
    return {
      data: null,
      loading: true,
      error: null
    }
  },
  mounted() {
    const year = this.year || this.$route.params.year;
    const id = this.id_student || this.$route.params.id_student || this.$route.params.id;
    apiFetch(`/grade/L/${year}/${id}`)
      .then(resp => {
        this.loading = false;
        if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
        return resp.json();
      })
      .then(json => {
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
  <div>
    <router-link to="/">← Retour</router-link>

    <div v-if="loading">Chargement des notes...</div>
    <div v-else-if="error" style="color: red">Erreur: {{ error }}</div>

    <div v-else-if="data">
      <h2>Notes Année {{ data.academic_year }} — Étudiant {{ data.student_id }}</h2>

      <div style="margin:12px 0">
        <strong>Moyenne annuelle :</strong>
        {{ data.annual_average !== null ? data.annual_average : '—' }}
        <span v-if="data.annual_mention"> — {{ data.annual_mention }}</span>
      </div>

      <div v-if="data.semesters">
        <div v-for="(sem, semId) in data.semesters" :key="semId" style="margin-bottom:20px;">
          <h3>Semestre {{ semId }}</h3>
          <div><strong>Moyenne semestre :</strong> {{ sem.average !== null ? sem.average : '—' }} <span v-if="sem.mention">— {{ sem.mention }}</span></div>

          <table style="width:100%; border-collapse:collapse; margin-top:8px;">
            <thead>
              <tr style="text-align:left; border-bottom:1px solid #ccc;">
                <th style="padding:6px">Matière</th>
                <th style="padding:6px">Crédit</th>
                <th style="padding:6px">Option</th>
                <th style="padding:6px">Note</th>
                <th style="padding:6px">Date examen</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="g in sem.grades" :key="g.subject_id" style="border-bottom:1px solid #eee;">
                <td style="padding:6px">{{ g.subject_name }}</td>
                <td style="padding:6px">{{ g.credit }}</td>
                <td style="padding:6px">{{ g.option_name || '-' }}</td>
                <td style="padding:6px">{{ g.grade !== null ? g.grade : '-' }}</td>
                <td style="padding:6px">{{ g.exam_date || '-' }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div v-else>
        Aucune information par semestre.
      </div>
    </div>

    <div v-else>
      Aucune donnée trouvée.
    </div>
  </div>
</template>

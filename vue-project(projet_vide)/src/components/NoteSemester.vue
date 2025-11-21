<script>

export default {
  
  // accept either props named 'semester'/'student' or 'id_semester'/'id_student' from the route
  props: ['semester', 'student', 'id_semester', 'id_student'],

  data() {
    return {
      gradeData: null,
      error: null,
      loading: true
    };
  },

  computed: {
    totalCredits() {
      if (!this.gradeData?.grades) return 0;
      return this.gradeData.grades.reduce((sum, grade) => sum + grade.credit, 0);
    },
    
    weightedAverage() {
      if (!this.gradeData?.grades || this.gradeData.grades.length === 0) return 0;
      const totalWeighted = this.gradeData.grades.reduce(
        (sum, grade) => sum + (grade.grade * grade.credit), 
        0
      );
      return (totalWeighted / this.totalCredits).toFixed(2);
    }
  },

  mounted() {
    // resolve ids from props or route params (robust to different naming)
    const semesterId = this.semester || this.id_semester || this.$route.params.id_semester || this.$route.params.semester;
    const studentId = this.student || this.id_student || this.$route.params.id_student || this.$route.params.student;

    if (!semesterId || !studentId) {
      this.error = 'Semester id or student id missing in route/props';
      this.loading = false;
      console.error('NoteSemester: missing ids', { semesterId, studentId });
      return;
    }

    fetch(`http://localhost:8085/grade/S/${encodeURIComponent(semesterId)}/${encodeURIComponent(studentId)}`)
      .then(response => {
        this.loading = false;
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then(json => { 
        console.log('JSON reçu:', json);
        this.gradeData = json.data || null;
      })
      .catch(error => {
        console.error('Erreur:', error);
        this.error = error.message;
        this.loading = false;
      });
  }
}
</script>

<template>
  <div>
    <p><router-link to="/">← Retour à la liste</router-link></p>

    <div v-if="loading">Chargement des notes...</div>

    <div v-else-if="error">Erreur : {{ error }}</div>

    <div v-else-if="gradeData">
      <h1>Notes du Semestre {{ gradeData.semester_id }}</h1>
      <p>Étudiant ID: {{ gradeData.student_id }}</p>

      <h3>Résumé</h3>
      <p>Nombre de matières : {{ gradeData.count }}</p>
      <p>Total crédits : {{ totalCredits }}</p>
      <p>Moyenne générale : {{ gradeData.average_common }}/20</p>
      <p>Mention : {{ gradeData.mention_common }}</p>

      <h3>Notes</h3>
      <table border="1" cellpadding="6">
        <thead>
          <tr>
            <th>Matière</th>
            <th>Crédits</th>
            <th>Note</th>
            <th>Date examen</th>
            <th>Option</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="grade in gradeData.grades" :key="grade.subject_id">
            <td>{{ grade.subject_name }}</td>
            <td>{{ grade.credit }}</td>
            <td>{{ grade.grade }}/20</td>
            <td>{{ grade.exam_date || '-' }}</td>
            <td>{{ grade.option_name || '-' }}</td>
          </tr>
        </tbody>
      </table>

      <div v-if="gradeData.options && gradeData.options.length">
        <h3>Options</h3>
        <ul>
          <li v-for="opt in gradeData.options" :key="opt.option_id">
            {{ opt.option_name }} — Moyenne: {{ opt.average }} — Mention: {{ opt.mention }}
          </li>
        </ul>
      </div>
    </div>

    <div v-else>Aucune donnée disponible</div>
  </div>
</template>

<!-- no styles - simple classic rendering -->
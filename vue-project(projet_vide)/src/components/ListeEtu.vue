<script>
export default {
  data() {
    return {
      students: [],
      error: null
    }    
  },

  mounted() {
    fetch('http://localhost:8085/student')
      .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then(json => { 
        console.log('JSON reçu:', json);
        this.students = json.data || [];
      })
      .catch(error => {
        console.error('Erreur:', error);
        this.error = error.message;
      });
  }
}
</script>

<template>
  <div>
    <h1>Liste des étudiants</h1>

    <div v-if="error" style="color: red;">
      Erreur : {{ error }}
    </div>

    <div v-else-if="students.length > 0">
      <ul>
        <li v-for="student in students" :key="student.id">
          <div><b>ID :</b> {{ student.id }}</div>
          <div><b>Nom :</b> {{ student.name }}</div>
          <div><b>Prénom :</b> {{ student.first_name }}</div>
          <div><b>Date de naissance :</b> {{ student.birth }}</div>
          <div><b>Code étudiant :</b> {{ student.student_code }}</div>
          <hr />
        </li>
      </ul>
    </div>

    <div v-else>
      Aucun étudiant dans la base
    </div>
  </div>
</template>
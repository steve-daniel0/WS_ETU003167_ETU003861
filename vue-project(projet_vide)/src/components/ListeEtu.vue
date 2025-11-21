<script>
export default {
  data() {
    return {
      students: [],
      error: null
    };
  },
  
  mounted() {
    fetch('http://localhost:8085/students')
      .then(response => {
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

    <div v-if="error" style="color: red;">
      Erreur : {{ error }}
    </div>

    <div v-else-if="students.length > 0">

      <table>
        <caption>Liste des étudiants</caption>

        <thead>
          <tr>
            <th>ETU</th>
            <th>Nom</th>
            <th>Moyenne S1</th>
            <th>Moyenne S2</th>
            <th>Moyenne S3</th>
            <th colspan="3">Moyenne S4</th>
            <th>Actions</th>
          </tr>

          <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>S4.1</th>
            <th>S4.2</th>
            <th>S4.3</th>
            <th></th>
          </tr>
        </thead>

        <tbody>

          <!-- Un étudiant par ligne -->
          <tr v-for="student in students" :key="student.Student.id">

            <!-- Infos de l'étudiant -->
            <td>{{ student.Student.student_code }}</td>
            <td>{{ student.Student.name }} {{ student.Student.first_name }}</td>

            <!-- S1 S2 S3 -->
            <td>{{ student.s['1']?.average_common || '—' }}</td>
            <td>{{ student.s['2']?.average_common || '—' }}</td>
            <td>{{ student.s['3']?.average_common || '—' }}</td>

            <!-- S4 (3 sous-options) -->
            <td>{{ student.s['4']?.options[0]?.average || '—' }}</td>
            <td>{{ student.s['4']?.options[1]?.average || '—' }}</td>
            <td>{{ student.s['4']?.options[2]?.average || '—' }}</td>

            <td>
              <router-link :to="`/etudiant/${student.Student.id}`">Voir détails</router-link>
            </td>

          </tr>

        </tbody>
      </table>

    </div>

    <div v-else>
      Aucun étudiant dans la base
    </div>

  </div>
</template>

<style>

table {
  border: 1px solid #ccc;
  border-collapse: collapse;
  margin: 0;
  padding: 0;
  width: 100%;
  table-layout: fixed;
}

table caption {
  font-size: 1.5em;
  margin: .5em 0 .75em;
}

table tr {
  background-color: #f8f8f8;
  border: 1px solid #ddd;
  padding: .35em;
}

table th,
table td {
  padding: .625em;
  text-align: center;
}

table th {
  font-size: .85em;
  letter-spacing: .1em;
  text-transform: uppercase;
}

@media screen and (max-width: 600px) {
  table {
    border: 0;
  }

  table caption {
    font-size: 1.3em;
  }
  
  table thead {
    border: none;
    clip: rect(0 0 0 0);
    height: 1px;
    margin: -1px;
    overflow: hidden;
    padding: 0;
    position: absolute;
    width: 1px;
  }
  
  table tr {
    border-bottom: 3px solid #ddd;
    display: block;
    margin-bottom: .625em;
  }
  
  table td {
    border-bottom: 1px solid #ddd;
    display: block;
    font-size: .8em;
    text-align: right;
  }
  
  table td::before {
    content: attr(data-label);
    float: left;
    font-weight: bold;
    text-transform: uppercase;
  }
  
  table td:last-child {
    border-bottom: 0;
  }
}

body {
  font-family: "Open Sans", sans-serif;
  line-height: 1.25;
}
</style>
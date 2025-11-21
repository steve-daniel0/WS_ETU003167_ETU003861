<script>
import { apiFetch } from '../api.js';

export default {
  props: ['id'],
  data() {
    return {
      student: null,
      error: null,
      loading: true
    };
  },

  mounted() {
    const studentId = this.id || this.$route.params.id;
    apiFetch(`/etu/${studentId}/details`)
      .then(response => {
        this.loading = false;
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then(json => { 
        console.log('JSON reçu:', json);
        this.student = json.data || null;
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
    <router-link to="/">← Retour</router-link>

    <div v-if="loading">Chargement...</div>

    <div v-else-if="error" style="color: red;">Erreur : {{ error }}</div>

    <div v-else-if="student && student.Student">
      <div class="student-profile py-4">
        <div class="container">
          <div class="row">
            <div class="col-lg-4">
              <div class="card shadow-sm">
                <div class="card-header bg-transparent text-center">
                  <img class="profile_img" src="https://placeimg.com/640/480/arch/any" alt="">
                  <h3>{{ student.Student.name }} {{ student.Student.first_name }}</h3>
                  <div>{{ student.Student.student_code }} — né(e) le {{ student.Student.birth }}</div>
                </div>
              </div>
            </div>

            <div class="col-lg-8">
              <div class="card shadow-sm">
                <div class="card-header bg-transparent border-0">
                  <h3 class="mb-0">Résumé des moyennes</h3>
                </div>
                <div class="card-body pt-0">
                  <table class="table table-bordered">
                    <tr>
                      <th>Semestre</th>
                      <th>Moyenne</th>
                    </tr>
                    <tr>
                      <td>S1</td>
                      <td>{{ student.s['1']?.average_common || '—' }}</td>
                    </tr>
                    <tr>
                      <td>S2</td>
                      <td>{{ student.s['2']?.average_common || '—' }}</td>
                    </tr>
                    <tr>
                      <td>S3</td>
                      <td>{{ student.s['3']?.average_common || '—' }}</td>
                    </tr>
                    <tr>
                      <td>S4 (tronc commun)</td>
                      <td>{{ student.s['4']?.average_common || '—' }}</td>
                    </tr>
                  </table>

                  <div v-if="student.s['4']?.options && student.s['4'].options.length > 0">
                    <h4>Options pour S4</h4>
                    <ul>
                      <li v-for="opt in student.s['4'].options" :key="opt.option_id">
                        <b>{{ opt.option_name }}</b> — Moyenne : {{ opt.average }}
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <p>        
        <router-link :to="`/grade/L/1/${student.Student.id}`">Note L1</router-link>
      </p>
      <p>        
        <router-link :to="`/grade/L/2/${student.Student.id}`">Note L2</router-link>
      </p>

    </div>

    <div v-else>
      Aucun étudiant trouvé
    </div>
  </div>
</template>

<style>
body {
  padding: 0;
  margin: 0;
  font-family: 'Lato', sans-serif;
  color: #000;
}

.student-profile .card {
  border-radius: 10px;
}

.student-profile .card .card-header .profile_img {
  width: 150px;
  height: 150px;
  object-fit: cover;
  margin: 10px auto;
  border: 10px solid #ccc;
  border-radius: 50%;
}

.student-profile .card h3 {
  font-size: 20px;
  font-weight: 700;
}

.student-profile .card p {
  font-size: 16px;
  color: #000;
}

.student-profile .table th,
.student-profile .table td {
  font-size: 14px;
  padding: 5px 10px;
  color: #000;
}
</style>
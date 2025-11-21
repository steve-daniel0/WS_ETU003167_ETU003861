<template>
  <div class="login">
    <h2>Connexion</h2>
    <form @submit.prevent="login">
      <div>
        <label>Nom d'utilisateur</label>
        <input v-model="username" type="text" required />
      </div>
      <div>
        <label>Mot de passe</label>
        <input v-model="password" type="password" required />
      </div>
      <button type="submit">Se connecter</button>
    </form>

    <!-- Affichage de l'erreur -->
    <p v-if="error" style="color:red">{{ error }}</p>
  </div>
</template>

<script>
// Import du helper apiFetch pour centraliser le token
import { apiFetch } from '../api.js'; // chemin vers ton helper

export default {
  data() {
    return {
      username: '',
      password: '',
      error: ''
    };
  },
  methods: {
    async login() {
      try {
        // Appel au backend Flight PHP
        const response = await apiFetch('/login', {
          method: 'POST',
          body: JSON.stringify({
            username: this.username,
            password: this.password
          })
        });

        // Stockage du token dans localStorage
        localStorage.setItem('token', response.token);

        // Réinitialiser l'erreur
        this.error = '';

        // Affichage d'un message ou redirection
        alert('Connexion réussie ! Token stocké.');

        // Exemple de redirection vers une page protégée
        this.$router.push('/etudiants');

      } catch (err) {
        // Affichage du message d'erreur depuis le backend
        this.error = err.message || 'Erreur lors de la connexion';
      }
    }
  }
};
</script>

<style scoped>
.login {
  max-width: 400px;
  margin: 50px auto;
}
form div {
  margin-bottom: 15px;
}
button {
  padding: 10px 20px;
}
</style>

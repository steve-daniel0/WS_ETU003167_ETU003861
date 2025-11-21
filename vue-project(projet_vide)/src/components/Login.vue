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
    <p v-if="error" style="color:red">{{ error }}</p>
  </div>
</template>

<script>
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
        const response = await fetch('http://localhost/myapp/login', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            username: this.username,
            password: this.password
          })
        });

        const data = await response.json();

        if (response.ok) {
          // Stocker le token dans localStorage pour les prochaines requêtes
          localStorage.setItem('token', data.token);
          this.error = '';
          alert('Connexion réussie ! Token stocké.');
          // Ici tu peux rediriger vers une route protégée
        } else {
          this.error = data.message;
        }
      } catch (err) {
        console.error(err);
        this.error = "Erreur serveur";
      }
    }
  }
};
</script>

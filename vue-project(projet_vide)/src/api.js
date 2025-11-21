// src/api.js
const BASE_URL = "http://localhost:8085";

function getToken() {
  return localStorage.getItem('token');
}

export async function apiFetch(url, options = {}) {
  const headers = {
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${getToken()}`,
    ...options.headers
  };

  const response = await fetch(`${BASE_URL}${url}`, {
    ...options,
    headers
  });

  const data = await response.json();

  if (!response.ok) {
    throw new Error(data.message || 'Erreur API');
  }

  return data;
}

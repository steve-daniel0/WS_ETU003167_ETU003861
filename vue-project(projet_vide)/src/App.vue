<template>
	<div>
		<nav style="padding:12px; border-bottom:1px solid #ddd; margin-bottom:12px;">
			<router-link to="/">Semestres</router-link>
			<span style="margin-left:12px;"></span>
			<router-link to="/">Liste des semestres</router-link>
		</nav>

		<main style="padding:12px;">
			<router-view />
		</main>
	</div>
</template>

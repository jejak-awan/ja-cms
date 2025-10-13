<template>
  <div>
    <h2>Articles</h2>
    <ul>
      <li v-for="article in articles" :key="article.id">
        {{ article.title }}
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const articles = ref([]);

onMounted(async () => {
  const res = await fetch('/articles');
  articles.value = await res.json();
});
</script>

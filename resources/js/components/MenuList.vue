<template>
  <div>
    <h2>Menus</h2>
    <ul>
      <li v-for="menu in menus" :key="menu.id">
        {{ menu.name }}
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const menus = ref([]);

onMounted(async () => {
  const res = await fetch('/menus');
  menus.value = await res.json();
});
</script>

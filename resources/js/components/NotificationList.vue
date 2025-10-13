<template>
  <div>
    <h2>Notifications</h2>
    <ul>
      <li v-for="notification in notifications" :key="notification.id">
        {{ notification.name }}
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const notifications = ref([]);

onMounted(async () => {
  const res = await fetch('/notifications');
  notifications.value = await res.json();
});
</script>

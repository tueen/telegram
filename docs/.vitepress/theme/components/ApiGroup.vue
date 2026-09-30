<script setup lang="ts">
defineProps<{
  title?: string
  description?: string
  count?: number | string
}>()
</script>

<template>
  <div class="api-group">
    <div v-if="title || $slots.header" class="api-group-header">
      <slot name="header">
        <h3 v-if="title" class="api-group-title">
          {{ title }}
          <span v-if="count !== undefined" class="api-group-count">{{ count }}</span>
        </h3>
        <p v-if="description" class="api-group-desc">{{ description }}</p>
      </slot>
    </div>
    <div class="api-group-list">
      <slot />
    </div>
  </div>
</template>

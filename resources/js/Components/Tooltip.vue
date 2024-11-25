<template>
  <div class="relative inline-block">
    <slot></slot>
    <div 
      v-show="show"
      class="absolute z-50 px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm tooltip dark:bg-gray-700"
      :class="position"
    >
      {{ text }}
      <div class="tooltip-arrow" data-popper-arrow></div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({
  text: {
    type: String,
    required: true
  },
  position: {
    type: String,
    default: 'top',
    validator: (value) => ['top', 'bottom', 'left', 'right'].includes(value)
  }
});

const show = ref(false);

// Position classes
const positionClasses = {
  top: '-top-10 left-1/2 -translate-x-1/2',
  bottom: 'top-full left-1/2 -translate-x-1/2 mt-2',
  left: 'right-full top-1/2 -translate-y-1/2 -ml-2',
  right: 'left-full top-1/2 -translate-y-1/2 ml-2'
};

// Event handlers
const showTooltip = () => show.value = true;
const hideTooltip = () => show.value = false;

onMounted(() => {
  const parent = document.querySelector('[data-tooltip]');
  if (parent) {
    parent.addEventListener('mouseenter', showTooltip);
    parent.addEventListener('mouseleave', hideTooltip);
    parent.addEventListener('focus', showTooltip);
    parent.addEventListener('blur', hideTooltip);
  }
});

onUnmounted(() => {
  const parent = document.querySelector('[data-tooltip]');
  if (parent) {
    parent.removeEventListener('mouseenter', showTooltip);
    parent.removeEventListener('mouseleave', hideTooltip);
    parent.removeEventListener('focus', showTooltip);
    parent.removeEventListener('blur', hideTooltip);
  }
});
</script>

<style scoped>
.tooltip {
  white-space: nowrap;
}
</style>

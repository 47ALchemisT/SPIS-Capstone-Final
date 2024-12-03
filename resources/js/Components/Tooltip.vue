<template>
  <div 
    class="relative inline-block" 
    @mouseenter="showTooltip"
    @mouseleave="hideTooltip"
    @focus="showTooltip"
    @blur="hideTooltip"
  >
    <slot></slot>
    <div 
      v-show="show"
      class="absolute z-50 px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg shadow-sm tooltip dark:bg-gray-700"
      :class="[positionClasses[position]]"
      role="tooltip"
    >
      {{ text }}
      <div class="tooltip-arrow" data-popper-arrow></div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

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
</script>

<style scoped>
.tooltip {
  white-space: nowrap;
  pointer-events: none;
}
</style>

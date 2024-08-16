<template>
  <div id="toast" class="fixed -top-24 left-[50%] translate-x-[-50%] opacity-0 z-50">
    <div class="flex items-center gap-4 bg-red-600 p-4 rounded-lg text-white">
      <ErrorsIcon />
      <span class="w-fit max-w-64 font-outfit font-semibold text-lg text-center">{{
        toaster.message
      }}</span>
    </div>
  </div>
</template>

<script setup>
import ErrorsIcon from './icons/ErrorsIcon.vue'
import { UseToaster } from '@/stores/toaster'

const toaster = UseToaster()
</script>

<style scoped>
.toast {
  animation: toast-move 3s linear;
}

@keyframes toast-move {
  10% {
    top: -32px;
    opacity: 20%;
  }
  15% {
    top: 32px;
    opacity: 60%;
  }
  20% {
    top: 96px;
    opacity: 100%;
  }
  90% {
    top: 96px;
    opacity: 100%;
  }
  100% {
    top: -96px;
    opacity: 0;
  }
}
</style>

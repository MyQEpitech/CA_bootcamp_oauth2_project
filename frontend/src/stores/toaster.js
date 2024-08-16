import { defineStore } from 'pinia'

export const UseToaster = defineStore('toaster', {
  state: () => ({
    message: ''
  }),
  actions: {
    triggerToast(toastMessage) {
      this.message = toastMessage
      const toast = document.getElementById('toast')
      toast.classList.add('toast')
      setTimeout(() => {
        toast.classList.remove('toast')
      }, 3000)
    }
  }
})

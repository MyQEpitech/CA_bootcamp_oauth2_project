<template>
  <main class="h-screen w-screen px-8 flex flex-col justify-center items-center">
    <div class="md:w-2/3 lg:w-2/5 max-w-[600px] flex flex-col gap-4">
      <h1 class="text-2xl md:text-3xl text-center font-semibold">
        <span>Oauth2<span class="text-blue-500">App</span></span>
      </h1>
      <form
        @submit.prevent="registerUser()"
        class="w-full flex flex-col items-center gap-4 p-8 md:shadow-lg rounded-xl"
      >
        <h2 class="text-center text-xl md:text-2xl">Register</h2>
        <div class="w-full">
          <span v-if="errors.username" class="text-red-500 text-sm font-medium">{{
            errors.username[0]
          }}</span>
          <input
            type="text"
            v-model="form.username"
            placeholder="Username"
            class="w-full border-2 border-blue-950 text-black focus:outline-none p-2 rounded-md"
          />
        </div>
        <div class="w-full">
          <span v-if="errors.email" class="text-red-500 text-sm font-medium">{{
            errors.email[0]
          }}</span>
          <input
            type="text"
            v-model="form.email"
            placeholder="Email"
            class="w-full border-2 border-blue-950 text-black focus:outline-none p-2 rounded-md"
          />
        </div>
        <div class="w-full">
          <span
            v-if="errors.password"
            v-for="error in errors.password"
            class="text-red-500 text-sm font-medium"
            >{{ error }} <br
          /></span>
          <input
            type="password"
            v-model="form.password"
            placeholder="Password"
            class="w-full border-2 border-blue-950 text-black focus:outline-none p-2 rounded-md"
          />
        </div>
        <input
          type="password"
          v-model="form.password_confirmation"
          placeholder="Confirm password"
          class="w-full border-2 border-blue-950 text-black focus:outline-none p-2 rounded-md"
        />
        <div class="relative">
          <div v-if="loading" class="absolute h-full w-full bg-white bg-opacity-50">
            <div class="h-full flex space-x-2 justify-center items-center">
              <span class="sr-only">Loading...</span>
              <div
                class="h-4 w-4 bg-black rounded-full animate-bounce [animation-delay:-0.3s]"
              ></div>
              <div
                class="h-4 w-4 bg-blue-600 rounded-full animate-bounce [animation-delay:-0.15s]"
              ></div>
              <div class="h-4 w-4 bg-blue-400 rounded-full animate-bounce"></div>
            </div>
          </div>
          <button
            type="submit"
            class="w-fit bg-blue-500 text-white font-medium p-2 mt-4 rounded-md"
          >
            Submit
          </button>
        </div>
        <div class="flex flex-col gap-2">
          <span>Or sign up with :</span>
          <div class="flex gap-4 items-center">
            <a :href="oauth2Service.getGoogleUrl(from)"><GoogleIcon /></a>
            <div><FacebookIcon /></div>
            <div><GithubIcon class="size-8" /></div>
          </div>
        </div>
        <div class="text-center lg:mt-4">
          <span>Already have an account ? </span>
          <RouterLink to="/login" class="text-blue-700 font-semibold"> Login here</RouterLink>
        </div>
      </form>
    </div>
  </main>
</template>

<script setup>
import FacebookIcon from '@/components/icons/FacebookIcon.vue'
import GithubIcon from '@/components/icons/GithubIcon.vue'
import MicrosoftIcon from '@/components/icons/MicrosoftIcon.vue'
import GoogleIcon from '@/components/icons/GoogleIcon.vue'
import router from '@/router'
import AuthService from '@/services/auth'
import { UseToaster } from '@/stores/toaster'
import { reactive, ref } from 'vue'
import { RouterLink } from 'vue-router'
import OAuth2Service from '@/services/oauth2'

const authService = new AuthService()
const oauth2Service = new OAuth2Service()
const toaster = UseToaster()

const from = '/'

let form = reactive({
  username: '',
  email: '',
  password: '',
  password_confirmation: ''
})
let errors = ref({})
let loading = ref(false)

const registerUser = async () => {
  loading.value = true

  const user = {
    username: form.username,
    email: form.email,
    password: form.password,
    password_confirmation: form.password_confirmation
  }

  let response = await authService.register(user)

  if (response.ok) {
    loading.value = false
    router.push('/')
  } else {
    loading.value = false
    if (response.errors) {
      errors.value = response.errors
    } else {
      setTimeout(() => {
        toaster.triggerToast(response.message)
      }, 200)
    }
  }
}
</script>

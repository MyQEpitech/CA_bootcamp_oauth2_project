import axios from 'axios'

export default class AuthService {
  register(user) {
    const userInfo = {
      username: user.username,
      email: user.email,
      password: user.password,
      password_confirmation: user.password_confirmation
    }
    return axios
      .post('register', userInfo)
      .then((response) => {
        if (response.status === 201) {
          const userData = {
            user: response.data.user,
            token: response.data.token
          }
          localStorage.setItem('userData', JSON.stringify(userData))
          return { ok: true }
        }
      })
      .catch((axiosError) => {
        if (axiosError.response && axiosError.response.status === 422) {
          return { ok: false, errors: axiosError.response.data.errors }
        } else {
          return {
            ok: false,
            message: 'Error creating account. Please check your internet connection.'
          }
        }
      })
  }

  login(credentials) {
    const userInfo = {
      login: credentials.login,
      password: credentials.password
    }
    return axios
      .post('login', userInfo)
      .then((response) => {
        if (response.status === 200) {
          const userData = {
            user: response.data.user,
            token: response.data.token
          }
          localStorage.setItem('userData', JSON.stringify(userData))
          return { ok: true }
        }
      })
      .catch((axiosError) => {
        if (axiosError.response) {
          if (axiosError.response.status === 422)
            return { ok: false, errors: axiosError.response.data.errors }
          else if (axiosError.response.status === 401)
            return { ok: false, errors: axiosError.response.data.errors }
        } else return { ok: false, message: 'Login error. Please check your internet connection.' }
      })
  }

  logout() {
    return axios
      .get('logout')
      .then(() => {
        return { ok: true }
      })
      .catch(() => {
        return { ok: false, message: 'Error. Please check your internet connection.' }
      })
  }
}

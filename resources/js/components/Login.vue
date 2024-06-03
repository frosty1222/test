<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useMessage } from 'naive-ui';
import { useRouter } from 'vue-router';
const router = useRouter();
const emailError = ref("")
const message = useMessage();
const FormValue = ref({
    email:"",
    password:""
})
const formRef = ref(null)
const baseUrl = `http://127.0.0.1:8000/api/auth/`

const handleSubmit = async()=>{
    const response = await axios.post(baseUrl + 'login',FormValue.value);
    if(response.data.status === "success"){
        message.success(response.data.message)
        localStorage.setItem('token', response.data.data.token);
        router.push('/admin')
    }else{
        emailError.value = response.data.message
    }
}
</script>

<template>
     <div class="body">
        <div class="login-form">
            <span class="text-danger" v-if="emailError !==''">{{ emailError }}</span>
            <form ref="formRef" :model="FormValue" @submit.prevent="handleSubmit">
                <h1 class="h3 mb-3 font-weight-normal text-center">Please sign in</h1>
                <div class="form-group">
                <label for="inputEmail">Email address</label>
                <input type="email"  v-model="FormValue.email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
                </div>
                <div class="form-group">
                <label for="inputPassword">Password</label>
                <input type="password" v-model="FormValue.password"  id="inputPassword" class="form-control" placeholder="Password" required>
                </div>
                <div class="checkbox mb-3">
                <label>
                    <input type="checkbox" value="remember-me"> Remember me
                </label>
                </div>
                <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
                <a href="/register" class="register-link">Go to Register</a>
                <p class="mt-5 mb-3 text-muted text-center">&copy; 2024</p>
            </form>
            </div>
     </div>
</template>
<style lang="scss" scoped>
   body {
    background-color: #f8f9fa;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
.login-form {
    width: 100%;
    max-width: 400px;
    padding: 15px;
    margin: auto;
  }
  .login-form {
    .form-control {
        position: relative;
        box-sizing: border-box;
        height: auto;
        padding: 10px;
        font-size: 16px;
        &:focus{
            z-index: 2;
        }
      }
  }
  .login-form {
    .form-group {
        margin-bottom: 15px;
      }
      .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
      }
  }
  .register-link {
    display: block;
    text-align: center;
    margin-top: 15px;
  }
</style>
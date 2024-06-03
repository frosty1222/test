<script setup>
import { useMessage } from 'naive-ui';
import { ref } from 'vue'
import { useRouter } from 'vue-router';

const emailErr = ref('');
const nameErr = ref('');
const passwordErr = ref('');
const confirmErr = ref('');
const message = useMessage();
const router = useRouter();
const FormValue = ref({
    email:"",
    password:"",
    name:"",
    password_confirmation:""
})
const formRef = ref(null)
const baseUrl = `http://127.0.0.1:8000/api/auth/`

const handleSubmit = async()=>{
    const response = await axios.post(baseUrl + 'register',FormValue.value);
    if(response.data.status === "success"){
        message.success(response.data.message)
        router.push('/')
    }else if(response.data.success === false){
       let responseData = response.data;
       if(responseData.errors){
          if(responseData.errors.email){
            emailErr.value = responseData.errors.email[0]
          }
          if(responseData.errors.password){
            passwordErr.value = responseData.errors.password[0]
          }
          if(responseData.errors.name){
            nameErr.value = responseData.errors.name[0]
          }
       }else{
          return;
       }
    }else{
        message.warning(response.data.message)
    }
}
</script>

<template>
     <div class="body">
        <div class="form-container" >
            <div class="form-container">
                <form ref="formRef" :model="FormValue" @submit.prevent="handleSubmit">
                <h1 class="h3 mb-3 font-weight-normal text-center">Register</h1>
                <div class="form-group">
                    <label for="registerName">Name</label>
                    <input type="text" v-model="FormValue.name" id="registerName" class="form-control" placeholder="Full Name" required>
                    <span class="text-danger" v-if="nameErr !==''">{{ nameErr }}</span>
                </div>
                <div class="form-group">
                    <label for="registerEmail">Email address</label>
                    <input type="email" v-model="FormValue.email" id="registerEmail" class="form-control" placeholder="Email address" required>
                    <span class="text-danger" v-if="emailErr !==''">{{ emailErr }}</span>
                </div>
                <div class="form-group">
                    <label for="registerPassword">Password</label>
                    <input type="password" v-model="FormValue.password" id="registerPassword" class="form-control" placeholder="Password" required>
                    <span class="text-danger" v-if="passwordErr !==''">{{ passwordErr }}</span>
                </div>
                <div class="form-group">
                    <label for="registerRePassword">Confirm Password</label>
                    <input type="password" v-model="FormValue.password_confirmation" id="registerRePassword" class="form-control" placeholder="Confirm Password" required>
                    <span class="text-danger" v-if="confirmErr !==''">{{ confirmErr }}</span>
                </div>
                <button class="btn btn-lg btn-primary btn-block" type="submit">Submit</button>
                <a href="" class="login-link">Go to Login</a>
                <p class="mt-5 mb-3 text-muted text-center">&copy; 2024</p>
                </form>
            </div>
        </div>
     </div>
</template>
<style lang="scss" scoped>
   .body {
    background-color: #f8f9fa;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
    .form-container {
    width: 100%;
    max-width: 400px;
    padding: 15px;
    margin: auto;
    .form-control {
        position: relative;
        box-sizing: border-box;
        height: auto;
        padding: 10px;
        font-size: 16px;
        &:focus {
            z-index: 2;
          }
      }
      .form-group {
        margin-bottom: 15px;
      }
      .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
      }
  }
  .register-link, .login-link {
    display: block;
    text-align: center;
    margin-top: 15px;
  }
</style>
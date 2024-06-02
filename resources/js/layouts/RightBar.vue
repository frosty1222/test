<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
    data:function() {
        return{
            showInfo: false,
            count: 1
        }
    },
    methods:{
        showInfoHandler() {
            this.count++;
            if(this.count % 2 === 0){
                 this.showInfo = true;
            }else{
                 this.showInfo = false;
            }
        },
        logout() {
        }
    },
    created(){
        
    }
})
</script>
<template>
     <div class="content">
    <div class="profile">
      <div class="avatar" @click="showInfoHandler">
        <img src="/images/demo.jpg" alt="">
      </div>
      <div class="name" @click="showInfoHandler">
        Alex 
      </div>
      <div v-show="showInfo === true" class="info">
        <ul>
          <li>
            <button>Profile</button>
          </li>
          <li>
            <form @submit.prevent="logout">
              <button type="submit">Logout</button>
            </form>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>
<style lang="scss" scoped>

</style>

<script lang="ts">
import { defineComponent, ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import { useMessage } from 'naive-ui';

export default defineComponent({
  setup() {
    const showInfo = ref(false);
    const count = ref(1);
    const message = useMessage();
    const router = useRouter();
    const baseUrl = 'http://127.0.0.1:8000/api/auth/';

    const showInfoHandler = () => {
      count.value++;
      showInfo.value = count.value % 2 === 0;
    };

    const logout = async () => {
      try {
        const response = await axios.post(baseUrl + 'logout');
        if (response.data.status === 'success') {
          message.success(response.data.message);
          localStorage.removeItem('token');
          router.push('/');
        } else {
          message.warning(response.data.message);
        }
      } catch (error) {
        console.error('An error occurred:', error);
        message.error('An error occurred. Please try again.');
      }
    };

    return {
      showInfo,
      count,
      showInfoHandler,
      logout
    };
  }
});
</script>

<template>
  <div class="content">
    <div class="profile">
      <div class="avatar" @click="showInfoHandler">
        <img src="/images/demo.jpg" alt="avatar" />
      </div>
      <div class="name" @click="showInfoHandler">
        Alex
      </div>
      <div v-show="showInfo" class="info">
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
.info{
  ul{
    li{
      form{
        button{
          outline: none;
          border: none;
        }
      }
    }
  }
}
</style>

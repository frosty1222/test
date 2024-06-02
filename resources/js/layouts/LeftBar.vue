<script lang="ts">
import { defineComponent } from 'vue'
import {routes} from '../helpers/routes';
export default defineComponent({
    data:function() {
        return{
            isShowLeftBar: true,
            selectedIndex: null,
            routes:routes
        }
    },
    methods:{
        showBar() {
            this.isShowLeftBar = true;
        },
        hideBar() {
            this.isShowLeftBar = false;
        },
        showChildrenRoute(index:number) {
            console.log(index);
            
            this.selectedIndex = this.selectedIndex === index ? null : index;
        }
    },
    created(){
    }
})
</script>
<template>
   <div>
    <i v-if="!isShowLeftBar" class="bi bi-caret-right-fill" @click="showBar"></i>
    <div v-if="isShowLeftBar" class="left-side-bar">
      <div class="icon">
        <i class="bi bi-caret-left-fill" @click="hideBar"></i>
      </div>
      <div class="left-content">
        <i class="bi bi-x-circle" @click="hideBar"></i>
        <div class="logo">
          <img src="/images/bell.png" alt="">
          <span>Admin Theme</span>
        </div>
        <div class="list-items">
          <ul>
            <li v-for="(route, index) in routes" :key="index"   :class="route.name">
              <template v-if="!route.children || route.children.length === 0">
                    <router-link :to="route.path" :class="{ active: selectedIndex === index }">
                        <i :class="route.icon"></i>
                        <span  @click="showChildrenRoute(index)">{{ route.name }}</span>
                    </router-link>
              </template>
              <template v-else>
                <a>
                  <i :class="route.icon"></i>
                  <span>{{ route.name }}</span>
                </a>
                <ul v-if="selectedIndex === index">
                  <li v-for="(childRoute, childIndex) in route.children" :key="childIndex" class="children">
                    <a :href="childRoute.path">
                      <i :class="childRoute.icon"></i>{{ childRoute.name }}
                    </a>
                  </li>
                </ul>
              </template>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>
<style lang="scss" scoped>
     
</style>

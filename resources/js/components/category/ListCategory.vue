<script setup>
import { ref, computed, onMounted } from 'vue'
import AdminVue from '../../layouts/Admin.vue'
import axios from 'axios'
import { NTable, NPagination,NButton,NInput, useMessage,NModal, useLoadingBar } from 'naive-ui'
// Reactive variables
const baseUrl = process.env.APP_URL + `/api/category/`;
const data = ref([])
const search = ref("")
const id = ref([])
const formRef = ref(null);
const size = ref('medium')
const title = ref('Add new category')
const isEdit = ref(false)
const showModalForm = ref(false)
const loadingBar = useLoadingBar()
const formValue = ref(
  {
    name:"",
    id:null
  }
);
const rules = ({
  name: [
    {
      required: true,
      message: 'Name is required',
      trigger: 'blur'
    }
  ],
  id:{required:false}
})
const page = ref(1)
const pageSize = ref(5)
const total = ref(0)
const showModal = ref(false)
const message = useMessage();
const addNewCategory = ()=>{
  showModalForm.value = true
}
const editCategory = (data)=>{
  showModalForm.value = true
  isEdit.value = true;
  formValue.value = {
    name:data.name,
    id:data.id
  }
}
const cancelCallback = () => {
  showModal.value = false;
  message.success('Cancelled')
}
const collectId = (value,item)=>{
  if (value === true) {
    if (!id.value.includes(item.id)) {
      id.value.push(item.id);
    }
  } else {
    id.value = id.value.filter(id => id !== item.id);
  }
}

const submitCallback = async() => {
  showModal.value = false;
  if(id.value.length === 0){
    message.warning('please select at least one category id');
    return;
  }
  const response = await axios.delete(baseUrl + 'delete-category', {
      data: { id: id.value }
  });
  if(response.data.status === "success"){
    message.success(response.data.message)
    showModalForm.value = false
    id.value = []
    fetchData('')
  }else{
    message.warning(response.data.message)
  }

}
// Methods
const fetchData = async (search) => {
  try {
    const response = await axios.get(baseUrl + 'get-category-list', {
      params: {
        page: page.value,
        pageSize: pageSize.value,
        search:search
      }
    })
    let dataResponse = response?.data.data;
    data.value = dataResponse.categories.data;
    total.value = dataResponse.categories.data.total;
    loadingBar.finish();
  } catch (error) {
    console.error('Error fetching data:', error)
  }
}

const handlePageSizeChange = (newPageSize) => {
  pageSize.value = newPageSize
  page.value = 1 // Reset to first page on page size change
  fetchData('')
}
const handleSearch = (event)=>{
  let value = event;
  if(value){
    loadingBar.start();
    fetchData(value)
  }else{
    fetchData('')
  }
}
const handleDeleCate = ()=>{
  showModal.value = true
}
const handleSubmit = async ()=>{
   try {
    await formRef.value.validate()
    if(isEdit.value === false){
      const response = await axios.post(baseUrl + 'create-category', formValue.value)
      if(response.data.status === "success"){
        let messages = response.data.message;
        message.success(messages)
        fetchData('')
        showModalForm.value = false
      }else{
        let messages = response.data.message;
        message.warning(messages)
      }
    }else if(isEdit.value === true){
      const response = await axios.post(baseUrl + 'edit-category', formValue.value)
      if(response.data.status === "success"){
        let messages = response.data.message;
        message.success(messages)
        fetchData('')
        showModalForm.value = false
      }else{
        let messages = response.data.message;
        message.warning(messages)
      }
    }
    formValue.value = {}
   } catch (error) {
     console.log(error);
   }
}
// Computed property for paginated data
const paginatedData = computed(() => {
  const start = (page.value - 1) * pageSize.value
  const end = start + pageSize.value
  return data.value.slice(start, end)
})

// Fetch data when the component is mounted
onMounted(() => {
  fetchData('')
})
</script>

<template>
  <AdminVue>
    <div class="header-section">
      <div class="left-icon">
          <n-button strong secondary type="primary" @click="addNewCategory"><i class="bi bi-plus"></i></n-button>
          <n-button strong secondary type="error" @click="handleDeleCate"><i class="bi bi-trash"></i></n-button>
      </div>
      <div class="right-search">
        <n-input v-model:value="search" @change="handleSearch($event)" type="text" placeholder="Search by name" />
      </div>
    </div>
    <div>
      <n-table  :single-column="false" :single-line="true" :bordered="true" :striped="true">
        <thead>
            <tr>
                <th>No.</th>
                <th>Select</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(item, index) in data" :key="index">
            <td>{{ index + 1 }}</td>
            <td>
              <input type="checkbox" @change="collectId($event.target.checked,item)">
            </td>
            <td>{{ item.name }}</td>
            <td>
                <n-button strong secondary type="warning" @click="editCategory(item)">
                    <i class="bi bi-pencil"></i>
                </n-button>
            </td>
          </tr>
        </tbody>
    </n-table>
      <NPagination
        v-model:page="page"
        v-model:page-size="pageSize"
        :item-count="total"
        show-quick-jumper
        @update:page="fetchData"
        @update:page-size="handlePageSizeChange"
      />
      <n-modal
        v-model:show="showModal"
        preset="dialog"
        title="Confirm"
        content="Are you sure?"
        positive-text="Submit"
        negative-text="Cancel"
        @positive-click="submitCallback"
        @negative-click="cancelCallback"
      />
    </div>
    <n-modal v-model:show="showModalForm">
      <n-card
        style="width: 600px"
        :title="title"
        :bordered="false"
        size="huge"
        role="dialog"
        aria-modal="true"
      >
        <n-form
          ref="formRef"
          inline
          :label-width="80"
          :model="formValue"
          :rules="rules"
          :size="size"
        >
        <n-input type="hidden" v-model:value="formValue.id"></n-input>
        <div class="form-group">
          <n-form-item label="Name" path="name">
            <n-input v-model:value="formValue.name" placeholder="Input Name Category" required />
          </n-form-item>
        </div>
          <div class="form-group">
            <n-button type="primary" native-type="button" @click="handleSubmit">Save</n-button>
          </div>
        </n-form>
      </n-card>
    </n-modal>
  </AdminVue>
</template>

<style lang="scss" scoped>
    .header-section{
      display:flex;
      justify-content: right;
      column-gap: 20px;
      .left-icon{
        display: flex;
        justify-content: center;
        column-gap: 10px;
      }
    }
  .n-form.n-form--inline{
     display: block !important;
  }
</style>
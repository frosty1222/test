<script setup>
import { ref, onMounted } from 'vue'
import AdminVue from '../../layouts/Admin.vue'
import axios from 'axios'
import { useLoadingBar, useMessage } from 'naive-ui'

// Reactive variables
const data = ref([])
const page = ref(1)
const pageSize = ref(5)
const total = ref(0)
const search = ref("")
const baseUrl = import.meta.env.APP_URL + `/api/product/`
const baseUrlCate = import.meta.env.APP_URL + `/api/category/`
const showModal = ref(false)
const message = useMessage();
const id = ref([])
const formRef = ref(null);
const size = ref('medium')
const title = ref('Add new category')
const isEdit = ref(false)
const showModalForm = ref(false)
const optionCate= ref([])
const loadingBar = useLoadingBar()
const formValue = ref(
  {
    name:"",
    product_id:null,
    category_id:null,
    discount_price:"",
    actual_price:""
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
  category_id:{},
  discount_price:[
    {
      required: true,
      message: 'Discount price is required',
      trigger: 'blur'
    }
  ],
  actual_price:[
    {
      required: true,
      message: 'Actual price is required',
      trigger: 'blur'
    }
  ],
  product_id:{required:false}
})

// methods
const addNewProduct = ()=>{
  showModalForm.value = true
}
const editProduct = (data)=>{
  showModalForm.value = true
  isEdit.value = true;
  formValue.value = {
    name:data.name,
    product_id:data.id,
    discount_price:data.discount_price,
    actual_price:data.actual_price,
    category_id:data.category_id
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
    message.warning('please select at least one product id');
    return;
  }
  const response = await axios.delete(baseUrl + 'delete-product', {
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
const handleDeleCate = ()=>{
  showModal.value = true
}
const fetchData = async (search) => {
  try {
    const response = await axios.get(baseUrl +'get-product-list', {
      params: {
        page: page.value,
        per_page: pageSize.value,
        search:search
      }
    })
    if(response.data.status === "success"){
      const dataResponse = response?.data
      data.value = dataResponse?.data.products.data
      total.value = dataResponse?.data.products.total
      loadingBar.finish()
    }
  } catch (error) {
    console.error('Error fetching data:', error)
  }
}
const fetchDataCate = async (search) => {
  try {
    const response = await axios.get(baseUrlCate + 'get-category-list', {
      params: {
        search:search,
        page: page.value,
        pageSize: pageSize.value,
      }
    })
    if(response.data.status === "success"){
      let dataResponse = response?.data.data;
      optionCate.value = dataResponse.categories.data.map(category => ({
        label: category.name,
        value: category.id
      }))
    }
  } catch (error) {
    console.error('Error fetching data:', error)
  }
}

const handlePageChange = (newPage) => {
  page.value = newPage
  fetchData('')
}

const handlePageSizeChange = (newPageSize) => {
  pageSize.value = newPageSize
  page.value = 1
  fetchData('')
}

const refreshData = () => {
  fetchData('')
}
const handleSearch = (event)=>{
  let value = event;
  if(value){
    loadingBar.start()
    fetchData(value)
  }else{
    fetchData('')
  }
}
const handleSubmit = async ()=>{
   try {
    await formRef.value.validate()
    if(isEdit.value === false){
      const response = await axios.post(baseUrl + 'create-product', formValue.value)
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
      const response = await axios.post(baseUrl + 'edit-product', formValue.value)
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
// Fetch data when the component is mounted
onMounted(() => {
  fetchData('')
  fetchDataCate('')
})

</script>

<template>
  <AdminVue>
    <n-spin :show="isLoading">
      
    </n-spin>
    <div class="header-section">
      <div class="left-icon">
          <n-button strong secondary type="primary" @click="addNewProduct"><i class="bi bi-plus"></i></n-button>
          <n-button strong secondary type="error" @click="handleDeleCate"><i class="bi bi-trash"></i></n-button>
      </div>
      <div class="right-search">
        <n-input v-model:value="search" @change="handleSearch($event)" type="text" placeholder="Search by name" />
      </div>
    </div>
    <div>
      <n-table :bottom-bordered="true" :single-column="false" :single-line="true" :bordered="true" :striped="true"  :data="data">
        <thead>
          <tr>
            <th>No.</th>
            <th>Select</th>
            <th>Name</th>
            <th>Discount price</th>
            <th>Actual price</th>
            <th>Category name</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in data" :key="item.id">
            <td>{{ (page - 1) * pageSize + index + 1 }}</td>
            <td><input type="checkbox" @change="collectId($event.target.checked,item)" /></td>
            <td>{{ item.name }}</td>
            <td>{{ item.discount_price }}</td>
            <td>{{ item.actual_price }}</td>
            <td>{{ item.category?.name }}</td>
            <td>
              <n-button strong secondary type="warning" @click="editProduct(item)">
                <i class="bi bi-pencil"></i>
              </n-button>
            </td>
          </tr>
        </tbody>
      </n-table>
      <n-pagination
        v-model:page="page"
        :item-count="total"
        show-quick-jumper
        @update:page="handlePageChange"
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
          v-show="showModalForm"
        >
        <n-form
          ref="formRef"
          inline
          :label-width="80"
          :model="formValue"
          :rules="rules"
          :size="size"
          v-show="showModalForm"
        >
        <n-input type="hidden" v-model:value="formValue.id"></n-input>
        <div class="form-group">
          <n-form-item label="Name" path="name">
            <n-input v-model:value="formValue.name" placeholder="Input Name" required />
          </n-form-item>
          <n-form-item label="Discount price" path="discount_price">
            <n-input v-model:value="formValue.discount_price" placeholder="Input discount price" required />
          </n-form-item>

          <n-form-item label="Name" path="name">
            <n-input v-model:value="formValue.actual_price" placeholder="Input actual price" required />
          </n-form-item>          
          <div class="form-group">
              <label for=""></label>
              <select class="form-control" name="" id="" v-model="formValue.category_id" required>
                <option></option>
                <option v-for="(data,index) in  optionCate" v-bind:key="index" :value="data.value">{{ data.label }}</option>
              </select>
            </div>
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
     display: block;
    }
</style>

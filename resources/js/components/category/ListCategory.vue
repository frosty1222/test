<script setup>
import { ref, computed, onMounted } from 'vue'
import AdminVue from '../../layouts/Admin.vue'
import axios from 'axios'
import { NTable, NPagination,NButton } from 'naive-ui'
// Reactive variables
const count = ref(0)
const data = ref([])
const columns = ref([
  {
    title: 'Name',
    key: 'name'
  }
])
const page = ref(1)
const pageSize = ref(5)
const total = ref(0)
const baseUrl = `http://127.0.0.1:8000/api/category/`
// Methods
const fetchData = async () => {
  try {
    const response = await axios.get(baseUrl + 'get-category-list', {
      params: {
        page: page.value,
        pageSize: pageSize.value
      }
    })
    let dataResponse = response?.data.data;
    data.value = dataResponse.categories.data;
    total.value = dataResponse.categories.data.total;
  } catch (error) {
    console.error('Error fetching data:', error)
  }
}

const handlePageSizeChange = (newPageSize) => {
  pageSize.value = newPageSize
  page.value = 1 // Reset to first page on page size change
  fetchData()
}

const refreshData = () => {
  fetchData()
}

// Computed property for paginated data
const paginatedData = computed(() => {
  const start = (page.value - 1) * pageSize.value
  const end = start + pageSize.value
  return data.value.slice(start, end)
})

// Fetch data when the component is mounted
onMounted(() => {
  fetchData()
})
</script>

<template>
  <AdminVue>
    <div>
      <n-table bottom-bordered="true" single-column="false" :single-line="true" bordered="true" striped="true">
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
            <td><input type="checkbox" /></td>
            <td>{{ item.name }}</td>
            <td>
                <n-button strong secondary type="warning">
                    <i class="bi bi-trash"></i>
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

    </div>
  </AdminVue>
</template>

<style lang="scss" scoped>

</style>
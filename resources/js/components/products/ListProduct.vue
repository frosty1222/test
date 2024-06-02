<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import AdminVue from '../../layouts/Admin.vue'
import axios from 'axios'
import { NTable, NPagination, NButton } from 'naive-ui'

// Reactive variables
const data = ref([])
const page = ref(1)
const pageSize = ref(5)
const total = ref(0)
const baseUrl = `http://127.0.0.1:8000/api/product/`

// Methods
const fetchData = async () => {
  try {
    const response = await axios.get(baseUrl +'get-product-list', {
      params: {
        page: page.value,
        per_page: pageSize.value
      }
    })
    const dataResponse = response?.data
    console.log(dataResponse);
    data.value = dataResponse?.data.products.data
    total.value = dataResponse?.data.products.total
    console.log('Fetched data:', data.value)
    console.log('Total items:', total.value)
  } catch (error) {
    console.error('Error fetching data:', error)
  }
}

const handlePageChange = (newPage) => {
  page.value = newPage
  fetchData()
}

const handlePageSizeChange = (newPageSize) => {
  pageSize.value = newPageSize
  page.value = 1
  fetchData()
}

const refreshData = () => {
  fetchData()
}

// Fetch data when the component is mounted
onMounted(() => {
  fetchData()
})

// Watch for changes in page and pageSize
// watch(page, fetchData)
// watch(pageSize, fetchData)
</script>

<template>
  <AdminVue>
    <div>
      <n-table bottom-bordered="true" single-column="false" :single-line="true" bordered="true" striped="true" :columns="columns" :data="data">
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
            <td><input type="checkbox" /></td>
            <td>{{ item.name }}</td>
            <td>{{ item.discount_price }}</td>
            <td>{{ item.actual_price }}</td>
            <td>{{ item.category?.name }}</td>
            <td>
              <n-button strong secondary type="warning">
                <i class="bi bi-trash"></i>
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
    </div>
  </AdminVue>
</template>

<style lang="scss" scoped>
/* Add your styles here */
</style>

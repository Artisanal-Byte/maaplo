<script setup>
defineProps({ reports: Array })
</script>

<template>
  <div>
    <h1 class="text-xl font-bold mb-4">Reported Errors</h1>
    <table class="table-auto w-full">
      <thead>
        <tr>
          <th>Description</th>
          <th>Error Type</th>
          <th>Page URL</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="report in reports" :key="report.id">
          <td>{{ report.description }}</td>
          <td>{{ report.error_type }}</td>
          <td><a :href="report.url" target="_blank">{{ report.url }}</a></td>
          <td>{{ new Date(report.created_at).toLocaleString() }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>



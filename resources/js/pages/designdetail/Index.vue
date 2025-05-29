<template>
  <div>
    <h1>Design Details</h1>
    <button @click="create">Create New</button>
    <table>
      <thead>
        <tr>
          <th>Body Section</th>
          <th>Gender</th>
          <th>Body Part</th>
          <th>Value</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="detail in designDetails" :key="detail.id">
          <td>{{ detail.body_section }}</td>
          <td>{{ detail.gender }}</td>
          <td>{{ detail.body_part }}</td>
          <td>{{ detail.value }}</td>
          <td>
            <button @click="edit(detail.id)">Edit</button>
            <button @click="destroy(detail.id)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  props: {
    designDetails: Array,
  },
  methods: {
    create() {
      this.$inertia.visit('/design-details/create');
    },
    edit(id) {
      this.$inertia.visit(`/design-details/${id}/edit`);
    },
    destroy(id) {
      if (confirm('Are you sure?')) {
        this.$inertia.delete(`/design-details/${id}`);
      }
    },
  },
};
</script>

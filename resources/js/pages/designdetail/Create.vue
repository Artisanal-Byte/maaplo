
<script>
export default {
  data() {
    return {
      form: {
        body_section: '',
        gender: 'm',
        body_part: '',
        value: '',
        image: null,
      },
    };
  },
  methods: {
    handleImageUpload(event) {
      this.form.image = event.target.files[0];
    },
    submit() {
      const formData = new FormData();
      for (const key in this.form) {
        formData.append(key, this.form[key]);
      }

      this.$inertia.post('/design-details', formData);
    },
  },
};
</script>
<template>
  <div>
    <h1>Create Design Detail</h1>
    <form @submit.prevent="submit">
      <label for="body_section">Body Section:</label>
      <input v-model="form.body_section" id="body_section" required />

      <label for="gender">Gender:</label>
      <select v-model="form.gender" id="gender" required>
        <option value="m">Male</option>
        <option value="f">Female</option>
        <option value="o">Other</option>
      </select>

      <label for="body_part">Body Part:</label>
      <input v-model="form.body_part" id="body_part" required />

      <label for="value">Value:</label>
      <input v-model="form.value" id="value" required />

      <label for="image">Image:</label>
      <input type="file" @change="handleImageUpload" id="image" required />

      <button type="submit">Save</button>
    </form>
  </div>
</template>


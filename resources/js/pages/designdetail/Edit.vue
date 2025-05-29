
<script>
import { useForm } from '@inertiajs/vue3'

export default {
  props: {
    designDetail: Object,
  },
  setup(props) {
    const form = useForm({
      body_section: props.designDetail.body_section,
      gender: props.designDetail.gender,
      body_part: props.designDetail.body_part,
      value: props.designDetail.value,
      image: null,
    })

    const handleImageUpload = (event) => {
      form.image = event.target.files[0]
    }

    const submit = () => {
      form.post(`/design-details/${props.designDetail.id}`, {
        preserveScroll: true,
        _method: 'put',
      })
    }

    return { form, submit, handleImageUpload }
  },
}
</script>
<template>
  <div>
    <h1>Edit Design Detail</h1>
    <form @submit.prevent="submit">
      <div>
        <label for="body_section">Body Section:</label>
        <input v-model="form.body_section" id="body_section" type="text" required />
      </div>

      <div>
        <label for="gender">Gender:</label>
        <select v-model="form.gender" id="gender" required>
          <option value="m">Male</option>
          <option value="f">Female</option>
          <option value="o">Other</option>
        </select>
      </div>

      <div>
        <label for="body_part">Body Part:</label>
        <input v-model="form.body_part" id="body_part" type="text" required />
      </div>

      <div>
        <label for="value">Value:</label>
        <input v-model="form.value" id="value" type="text" required />
      </div>

      <div>
        <label for="image">Image:</label>
        <input type="file" @change="handleImageUpload" id="image" />
      </div>

      <button type="submit">Update</button>
    </form>
  </div>
</template>


<template>
  <div>
    <form v-if="typeof entry !== 'undefined'" @submit.prevent="handleSubmit">
      <div v-for="(row, index) in rows" :key="index" class="row">
        <div v-for="(field, index) in row" :key="index" class="col-md-6 form-group">
          <label :for="field.name">{{ field.label }}</label>
          <input :type="field.type || 'text'" v-model="entry[field.name]" class="form-control">
        </div>
      </div>
      <br>
      <button type="submit">Submit</button>
    </form>
  </div>
</template>
  
<script lang="ts">
import { ref } from 'vue';

export default {
  name: 'TaskBoardFormular',
  props: {
      rows: {
          type: Array,
          required: true
      },
      entry: {
          type: Object,
          required: false
      },
  },
  setup(props, { emit }) {
      const formData = ref({});

      const handleSubmit = () => {
          emit('submitForm', { ...props.entry });
      };

      return {
          formData,
          handleSubmit
      };
  }
};
</script>
  
<style scoped>
</style>

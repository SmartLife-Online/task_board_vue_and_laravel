<template>
  <form v-if="typeof entry !== 'undefined'" class="form" @submit.prevent="handleSubmit">
    <div v-for="(row, rowIndex) in rows" :key="rowIndex" class="form-row">
      <div
        v-for="(field, colIndex) in row"
        :key="colIndex"
        class="form-field"
        :class="{ 'form-field-check': field?.type === 'checkbox' }"
      >
        <label :for="field.name">{{ field.label }}</label>
        <input
          v-if="field?.type === 'checkbox'"
          :id="field.name"
          type="checkbox"
          :checked="isCheckboxChecked(entry[field.name])"
          @change="updateCheckboxValue(field.name, $event)"
          class="form-check-input"
        >
        <input v-else :id="field.name" :type="field.type || 'text'" v-model="entry[field.name]" class="form-control" :autofocus="!rowIndex && !colIndex">
      </div>
    </div>
    <slot />
    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Submit</button>
    </div>
  </form>
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

      const isCheckboxChecked = (value: unknown): boolean => {
          return value === true || value === 1 || value === '1';
      };

      const updateCheckboxValue = (fieldName: string, event: Event): void => {
          const target = event.target as HTMLInputElement;

          if (!props.entry) {
              return;
          }

          props.entry[fieldName] = target.checked ? 1 : 0;
      };

      const handleSubmit = () => {
          emit('submitForm', { ...props.entry });
      };

      return {
          formData,
          isCheckboxChecked,
          updateCheckboxValue,
          handleSubmit
      };
  }
};
</script>
  
<style scoped>
</style>

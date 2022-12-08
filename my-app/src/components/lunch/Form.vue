<template>
  <form @submit.prevent="handleSubmit(item)">
    <div class="form-group">
      <label
        for="lunch_selectable"
        class="form-control-label">selectable</label>
        <input
          id="lunch_selectable"
          v-model="item.selectable"
          :class="['form-control', !isValid('selectable') ? 'is-invalid' : 'is-valid']"
          type="checkbox"
          placeholder="">
      <div
        v-if="!isValid('selectable')"
        class="invalid-feedback">{{ violations.selectable }}</div>
    </div>
    <div class="form-group">
      <label
        for="lunch_day"
        class="form-control-label">day</label>
        <select
          v-model="item.day"
          id="lunch_day"
          class="form-control"
        >
          <option v-for="selectItem in daySelectItems"
                  :key="selectItem['@id']"
                  :value="selectItem['@id']"
                  :selected="isSelected('day', selectItem['@id'])">{{ selectItem.name }}
          </option>
        </select>
      <div
        v-if="!isValid('day')"
        class="invalid-feedback">{{ violations.day }}</div>
    </div>
    <div class="form-group">
      <label
        for="lunch_number"
        class="form-control-label">number</label>
        <input
          id="lunch_number"
          v-model="item.number"
          :class="['form-control', !isValid('number') ? 'is-invalid' : 'is-valid']"
          type="number"
          placeholder="">
      <div
        v-if="!isValid('number')"
        class="invalid-feedback">{{ violations.number }}</div>
    </div>
    <div class="form-group">
      <label
        for="lunch_text"
        class="form-control-label">text</label>
        <input
          id="lunch_text"
          v-model="item.text"
          :class="['form-control', !isValid('text') ? 'is-invalid' : 'is-valid']"
          type="text"
          placeholder="">
      <div
        v-if="!isValid('text')"
        class="invalid-feedback">{{ violations.text }}</div>
    </div>
    <div class="form-group">
      <label
        for="lunch_users"
        class="form-control-label">users</label>
        <select
          v-model="item.users"
          id="lunch_users"
          multiple
          class="form-control"
        >
          <option v-for="selectItem in usersSelectItems"
                  :key="selectItem['@id']"
                  :value="selectItem['@id']"
                  :selected="isSelected('users', selectItem['@id'])">{{ selectItem.name }}
          </option>
        </select>
      <div
        v-if="!isValid('users')"
        class="invalid-feedback">{{ violations.users }}</div>
    </div>

    <button
      type="submit"
      class="btn btn-success">Submit</button>
  </form>
</template>

<script>
  import { find, get, isUndefined } from 'lodash';
  import { mapActions } from 'vuex';

  export default {
  props: {
    handleSubmit: {
      type: Function,
      required: true,
    },

    values: {
      type: Object,
      required: true,
    },

    errors: {
      type: Object,
      default: () => {},
    },

    initialValues: {
      type: Object,
      default: () => {},
    }
  },

  mounted() {
    this.dayGetSelectItems();
    this.usersGetSelectItems();
  },

  computed: {
    ...mapFields('day/list', {
      'daySelectItems': 'selectItems',
    }),
    ...mapFields('users/list', {
      'usersSelectItems': 'selectItems',
    }),

    // eslint-disable-next-line
    item() {
      return this.initialValues || this.values;
    },

    violations() {
      return this.errors || {};
    },
  },

  methods: {
    ...mapActions({
        dayGetSelectItems: 'day/list/getSelectItems',
        usersGetSelectItems: 'users/list/getSelectItems',
    }),

    isSelected(collection, id) {
      return find(this.item[collection], ['@id', id]) !== undefined;
    },

    isValid(key) {
      return isUndefined(get(this.violations, key));
    },
  },
};
</script>

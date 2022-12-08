<template>
  <div>
    <h1>Show Book {{ item && item['@id'] }}</h1>

    <div
      v-if="isLoading"
      class="alert alert-info"
      role="status">Loading...</div>
    <div
      v-if="error"
      class="alert alert-danger"
      role="alert">
      <span
        class="fa fa-exclamation-triangle"
        aria-hidden="true">{{ error }}</span>
    </div>
    <div
      v-if="deleteError"
      class="alert alert-danger"
      role="alert">
      <span
        class="fa fa-exclamation-triangle"
        aria-hidden="true">{{ deleteError }}</span>
    </div>
    <div
      v-if="item"
      class="table-responsive">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>Field</th>
            <th>Value</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th scope="row">selectable</th>
            <td>{{ item['selectable'] }}</td>
          </tr>
          <tr>
            <th scope="row">day</th>
            <td>{{ item['day'] }}</td>
          </tr>
          <tr>
            <th scope="row">number</th>
            <td>{{ item['number'] }}</td>
          </tr>
          <tr>
            <th scope="row">text</th>
            <td>{{ item['text'] }}</td>
          </tr>
          <tr>
            <th scope="row">users</th>
            <td>{{ item['users'] }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <router-link
      :to="{ name: 'LunchList' }"
      class="btn btn-primary">
      Back to list
    </router-link>
    <router-link
      v-if="item"
      :to="{ name: 'LunchUpdate', params: { id: item['@id'] } }"
      class="btn btn-warning">
      Edit
    </router-link>
    <button
      class="btn btn-danger"
      @click="del">Delete</button>
  </div>
</template>

<script>
import { mapActions } from 'vuex';
import { mapFields } from 'vuex-map-fields';
import ItemWatcher from '../../mixins/ItemWatcher';
import * as types from '../../store/modules/lunch/show/mutation_types';
import * as delTypes from '../../store/modules/lunch/delete/mutation_types';

export default {
  mixins: [ItemWatcher],
  computed: {
    ...mapFields('lunch/del', {
      deleteError: 'error',
      deleted: 'deleted',
      mercureDeleted: 'mercureDeleted',
    }),
    ...mapFields('lunch/show', {
      error: 'error',
      isLoading: 'isLoading',
      item: 'retrieved',
      hubUrl: 'hubUrl',
    }),
    itemUpdateMutation: () =>`lunch/show/${types.LUNCH_SHOW_SET_RETRIEVED}`,
    itemMercureDeletedMutation: () => `lunch/del/${delTypes.LUNCH_DELETE_MERCURE_SET_DELETED}`,
  },

  watch: {
    // eslint-disable-next-line object-shorthand,func-names
    deleted: function(deleted) {
      if (!deleted) {
        return;
      }

      this.$router.push({ name: 'LunchList' });
    },
    // eslint-disable-next-line object-shorthand,func-names
    mercureDeleted: function(deleted) {
      if (!deleted) {
        return;
      }

      this.$router.push({ name: 'LunchList' });
    },
  },

  beforeDestroy () {
    this.reset();
  },

  created () {
    this.retrieve(decodeURIComponent(this.$route.params.id));
  },

  methods: {
    ...mapActions({
      deleteItem: 'lunch/del/del',
      reset: 'lunch/show/reset',
      retrieve: 'lunch/show/retrieve',
    }),

    del() {
      if (window.confirm('Are you sure you want to delete this lunch?')) {
        this.deleteItem(this.item);
      }
    },
  },
};
</script>

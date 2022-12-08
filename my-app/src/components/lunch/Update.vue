<template>
  <div>
    <h1>Edit Book {{ item && item['@id'] }}</h1>

    <div
      v-if="created"
      class="alert alert-success"
      role="status">{{ created['@id'] }} created.</div>
    <div
      v-if="updated"
      class="alert alert-success"
      role="status">{{ updated['@id'] }} updated.</div>
    <div
      v-if="isLoading || deleteLoading"
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

    <LunchForm
      v-if="item"
      :handle-submit="onSendForm"
      :values="item"
      :errors="violations"
      :initial-values="item" />

    <router-link
      v-if="item"
      :to="{ name: 'LunchList' }"
      class="btn btn-primary">Back to list</router-link>
    <button
      class="btn btn-danger"
      @click="del">Delete</button>
  </div>
</template>

<script>
import { mapActions } from 'vuex';
import { mapFields } from 'vuex-map-fields';
import ItemWatcher from '../../mixins/ItemWatcher';
import LunchForm from './Form.vue';
import * as types from '../../store/modules/lunch/update/mutation_types';
import * as delTypes from '../../store/modules/lunch/delete/mutation_types';

export default {
  mixins: [ItemWatcher],
  components: {
    LunchForm,
  },

  computed: {
    ...mapFields('lunch/del', {
      deleteError: 'error',
      deleteLoading: 'isLoading',
      deleted: 'deleted',
      mercureDeleted: 'mercureDeleted',
    }),
    ...mapFields('lunch/create', {
      created: 'created',
    }),
    ...mapFields('lunch/update', {
      isLoading: 'isLoading',
      error: 'error',
      item: 'retrieved',
      hubUrl: 'hubUrl',
      updated: 'updated',
      violations: 'violations',
    }),
    itemUpdateMutation: () => `lunch/update/${types.SET_RETRIEVED}`,
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

  beforeDestroy() {
    this.reset();
  },

  created() {
    this.retrieve(decodeURIComponent(this.$route.params.id));
  },

  methods: {
    ...mapActions({
      createReset: 'lunch/create/reset',
      deleteItem: 'lunch/del/del',
      delReset: 'lunch/del/reset',
      retrieve: 'lunch/update/retrieve',
      updateReset: 'lunch/update/reset',
      update: 'lunch/update/update',
      updateRetrieved: 'lunch/update/updateRetrieved',
    }),

    del() {
      if (window.confirm('Are you sure you want to delete this lunch?')) {
        this.deleteItem(this.item);
      }
    },

    reset() {
      this.updateReset();
      this.createReset();
    },

    onSendForm() {
      this.update(this.item);
    },
  },
};
</script>

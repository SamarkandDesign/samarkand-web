<template>
  <div>
    <div class="row">
      <div class="col-sm-8 top-buffer">
        <div role="alert" class="alert alert-danger" v-if="this.errorMessage">
          <p>{{ this.errorMessage }}</p>
        </div>

        <div
          role="alert"
          class="top-buffer alert alert-success alert-block clearfix"
          v-if="submitted"
        >
          Thanks for your feedback.
          <a href="/" class="btn btn-primary pull-right">Back to home page</a>
        </div>

        <form
          v-bind:action="route"
          method="POST"
          class="top-buffer"
          @submit.prevent="submitForm"
          v-if="!submitted"
        >
          <div class="form-group" v-bind:class="{'has-error': errors.message && errors.message[0]}">
            <label class="control-label" for="message">Where did you hear about us?</label>
            <textarea
              id="message"
              class="form-control"
              rows="5"
              name="message"
              v-model="form.message"
            ></textarea>
            <span
              v-if="errors.message"
              class="text-danger"
            >{{ errors.message ? errors.message[0] : '' }}</span>
          </div>

          <button type="submit" class="btn btn-primary" id="send" v-bind:disabled="loading">
            <span v-show="loading">
              <i class="fa fa-circle-o-notch fa-spin fa-fw"></i> Loading...
            </span>
            <span v-show="!loading">Submit</span>
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { client } from '../client';

export default {
  props: {
    route: { type: String, required: true },
    orderId: { required: true },
  },
  data() {
    return {
      form: {
        message: '',
        order_id: this.orderId,
      },
      errors: {},
      errorMessage: '',
      loading: false,
      submitted: false,
    };
  },

  computed: {
    hasErrors() {
      return Object.keys(this.errors).length > 0 || this.errorMessage.length;
    },
  },
  methods: {
    submitForm() {
      if (this.loading) {
        return;
      }
      this.errorMessage = '';
      this.loading = true;
      this.errors = {};
      this.errorMessage = '';
      client
        .post(this.route, this.form)
        .then(response => {
          this.loading = false;
          this.submitted = true;
        })
        .catch(err => {
          this.loading = false;

          if (err.response && typeof err.response.data === 'object') {
            this.errors = err.response.data;
          } else {
            this.errorMessage =
              'Oops, it looks like something went wrong saving your submission. Please try again.';
          }
        });
    },
  },
};
</script>

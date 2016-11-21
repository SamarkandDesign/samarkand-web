<template>
  <div>

    <!-- Errors -->
    <div class="alert alert-danger" v-if="errors">
      <p>There were some errors with your input:</p>
      <ul>
        <li v-for="error in errors">{{ error }}</li>
      </ul>
    </div>

    <!-- Attribute Display -->
    <h2 v-show="productAttribute">{{ productAttribute.name }}</h2>

    <!-- Properties -->
    <div  v-if="productAttribute" class="row">

      <!-- Property Entry -->
      <div class="col-md-6">
        <div class="form-group">
          <label for="property">Add Property</label>
          <div class="input-group">
            <input class="form-control" type="text" v-model="property" @keyup.enter="addProperty">
            <div class="input-group-btn">
              <button class="btn btn-default" type="button" @click="addProperty" :disabled="loading">Add Property</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Attribute Display -->
      <div class="col-md-6">
        <ul class="list-group">
          <li v-for="(property, index) in orderedProperties"class="list-group-item">

            <div v-if="editingProperty !== property.id" style="max-width:50%; display:inline;">
              {{ property.name }} <span class='text-muted'>({{ property.order}})</span>
              <button class="btn btn-link" @click="editingProperty = property.id"><i class="fa fa-pencil"></i></button>
            </div>

            <div v-if="editingProperty == property.id" style="max-width:50%; display:inline-block;">

              <div class="input-group" >
                <input type="text" class="form-control input-sm" v-model="property.name" @keyup.enter="updateProperty(property)">
                <span class="input-group-btn">
                  <button class="btn btn-default btn-sm" type="button" @click="updateProperty(property)" title="Save Edit"><span class="text-success"><i class="fa fa-check"></i></span></button>
                  <button class="btn btn-default btn-sm" @click="editingProperty = null" title="Cancel Edit"><i class="fa fa-times"></i></button>
                </span>
              </div><!-- /input-group -->

            </div>

            <span class="pull-right" v-if="!editingProperty">
              <button class="btn-link" @click="promote(index)" :disabled="index === 0"><i class="fa fa-fw fa-arrow-up"></i></button>
              <button class="btn-link" @click="demote(index)" :disabled="index === (properties.length - 1)"><i class="fa fa-fw fa-arrow-down"></i></button>
              <button class="btn btn-link" @click="removeProperty(property)"><span class="text-danger"><i class="fa fa-fw fa-trash"></i></span></button>
            </span>
          </li>
        </ul>

        <!-- Loading messages -->
        <p class="form-group top-buffer">
          <span v-if="loading"><i class="fa fa-circle-o-notch fa-spin"></i> Working...</span>
          <span class="text-success" v-if="updatedMessage"> <i class="fa fa-check"></i> {{ updatedMessage }}</span>
        </p>

      </div>
    </div>
  </div>
</template>

<script>

import {sluggify, order} from '../filters'

export default {
  props: {
    productAttribute: {type: Object, required: true}
  },

  data () {
    return {
      'currentAttribute': null,
      'property': '',
      'properties': [],
      'errors': null,
      'loading': false,
      'editingProperty': null,
      'updatedMessage': '',
    };
  },

  mounted () {
    if (this.productAttribute) {
      this.fetchCurrentProperties();
    }
  },

  computed: {
    orderedProperties () {
      return order(this.properties, 'order')
    }
  },

  methods: {
    setAttribute () {
      this.productAttribute = this.currentAttribute;
      this.fetchCurrentProperties();
    },

    addProperty () {
      var property = {
        name: this.property,
        order: this.properties.length + 1,
      };

      this.loading = true;

      this.$http.post('/api/product_attributes/' + this.productAttribute.id + '/attribute_properties', property)
      .then(response => {
        this.properties.push(response.data);
        this.property = '';
        this.loading = false;
        this.showMessage('Property Saved');
      }).catch(response => {
        this.displayErrors(response.data);
        this.loading = false;
      });

    },

    removeProperty (property) {
      this.loading = true;
      const index = this.properties.indexOf(property)

      this.$http.delete('/api/attribute_properties/' + property.id).then(response => {
        this.properties.splice(index, 1)
        this.loading = false;
        this.showMessage('Property Deleted')
      }).catch(response => {
        console.warn(response)
        this.loading = false
      });
    },

    fetchCurrentProperties () {
      this.loading = true;

      this.$http.get('/api/attribute_properties/' + this.productAttribute.id)
      .then(response => {
        this.properties = response.data;
        this.loading = false;

        this.reindexItems();
      }).catch(response => {
        this.loading = false;
      });
    },

    switchAttribute () {
      this.currentAttribute = this.product_attribute;
      this.property = null;
      this.fetchCurrentAttributes();
    },

    displayErrors (errors) {
      this.errors = errors;

      var errorDisplayTime = 5000;

      // Wait a bit and reset the errors
      setTimeout(() => { this.errors = null }, errorDisplayTime);
    },

    reindexItems () {
      this.properties = order(this.properties, 'order').map((property, index) => {
        property.order = index
        return property
      })
    },

    promote (index) {
      this.reindexItems();

      if (index !== 0) {
        var newOrder = this.properties[index - 1].order;

        this.properties[index - 1].order = this.properties[index].order;
        this.updateProperty(this.properties[index - 1]);

        this.properties[index].order = newOrder;
        this.updateProperty(this.properties[index]);
      }
    },

    demote (index) {
      this.reindexItems();

      if ((index + 1) !== this.properties.length) {
        var newOrder = this.properties[index + 1].order;

        this.properties[index + 1].order = this.properties[index].order;
        this.updateProperty(this.properties[index + 1]);

        this.properties[index].order = newOrder;
        this.updateProperty(this.properties[index]);
      }
    },

    updateProperty (property) {
      this.loading = true;

      property.slug = sluggify(property.name)

      this.$http.patch('/api/attribute_properties/' + property.id, property)
      .then(response => {
        this.editingProperty = null;
        this.loading = false;
        this.showMessage('Property Updated');
      })
      .catch(response => {
        this.displayErrors(response.data);

        this.loading = false;
      });
    },

    showMessage (message) {
      this.updatedMessage = message;
      setTimeout(() => { this.updatedMessage = false }, 5000);
    },
  }
}

</script>

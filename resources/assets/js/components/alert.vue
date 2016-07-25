<template lang="html">
  <div>
  <div class="top-buffer alert alert-dismissable alert-{{ type }} {{ block ? 'alert-block' : '' }} clearfix" role="alert" v-if="!dismissed">
  	 <button type="button" class="close" aria-label="Close" @click="dismissed = true" v-if="dississable"><span aria-hidden="true">&times;</span></button>
  	<slot></slot>
  </div>
</div>
</template>

<script>
import coerceBoolean from '../utils/coerceBoolean'

export default {
  props: {
    type: {type: String, default: 'info'},
    dississable: {type: Boolean, default: true, coerce: coerceBoolean},
    block: {type: Boolean, default: false, coerce: coerceBoolean},
  },
  data: function () {
    return {
      dismissed: false
    }
  }
}
</script>

<template>
  <div>
  <form ref="dropzone" class="dropzone" v-bind:action="action" method="post">
     <input type="hidden" v-bind:value="csrfToken">
   </form>
 </div>
</template>

<script>
  import Dropzone from 'dropzone'
  import eventHub from '../eventHub'

  Dropzone.autoDiscover = false

  export default {
    props: ['action', 'csrf-token'],
    data () {
      return {
        dropzone: null
      }
    },
    mounted () {
      this.dropzone = new Dropzone(this.$refs.dropzone, {
        url: this.action,
        paramName: 'image',
        maxFilesize: 5,
        acceptedFiles: '.jpeg, .jpg, .png, .bmp, .gif, .svg'
      })

      this.dropzone.on('success', file => {
        this.dropzone.removeFile(file)
        eventHub.$emit('file-uploaded', {file})
      })
    }
  }
</script>
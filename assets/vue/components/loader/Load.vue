<template>
  <loader v-if="status === 'load'"></loader>
  <reload v-else-if="status === 'reload'" @reload="reload"></reload>
  <div v-else-if="status === 'success'"><slot></slot> </div>
  <p v-else>Status xato kiritilgan</p>
</template>

<script>
import Loader from './Loader'
import Reload from './Reload'
export default {
  name: 'Load',
  props: {
    status: String
  },
  components: {
    Loader,
    Reload
  },
  methods: {
    reload () {
      this.$emit('upload')
    }
  }
}
</script>

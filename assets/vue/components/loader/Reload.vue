<template>
  <button style="margin: auto" @click="refreshData">Reload</button>
</template>

<script>
export default {
  name: 'Reload',
  methods: {
    refreshData () {
      this.$emit('reload')
    }
  }
}
</script>

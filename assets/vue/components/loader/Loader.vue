<template>
  <div class="loader-position" >
    <div class="loader-container">
      <div class="loader-dummy"></div>
      <div class="loader-element">
        <div class="loader"></div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Loader'
}
</script>

<style scoped>
.loader-container {
  display: inline-block;
  position: relative;
  width: 15%;
}

.loader-dummy {
  margin-top: 100%;
  /* 4:3 aspect ratio */
}

.loader-element {
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
}
.loader-position {
  margin: auto;
  max-width: 500px;
  text-align: center;
}
.loader {
  border: 2px solid #f3f3f3;
  border-radius: 100%;
  border-top: 2px solid #7367F0;
  width: 100%;
  height: 100%;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
}

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

</style>

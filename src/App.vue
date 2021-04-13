<template>
  <div id="app">
    <div v-if="isConnected">
      <Home />
    </div>
    <div v-else>
      <Config @onConnect="isConnected = true" />
    </div>
  </div>
</template>

<script>
import Config from './views/Config';
import Home from './views/Home';

export default {
  components: { Home, Config },
  data: function() {
    return {
      isConnected: true
    }
  },
  name: 'App'
}
</script>

<!-- Styling with sass -->
<style lang="scss" scoped>
  #app {
    font-family: Helvetica, Arial, sans-serif;
  }
</style>

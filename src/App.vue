<template>
  <div id="app">
    <p class="app-content">Hello world</p>
    <button v-on:click="createItem">Create item</button>
    <button v-on:click="updateItem">Update item</button>
    <button v-on:click="deleteItem">Delete item</button>
  </div>
</template>

<script>
import API from './config/api';

export default {
  name: 'App',
  mounted: () => {
    API.getItems()
      .then(data => console.log(data))
      .catch(err => console.log(err)); 
  },
  methods: {
    createItem: () => {
      const itemData = {
        name: "test2",
        x: 15,
        y: 25,
        width: 50,
        height: 100
      };
      API.createItem(itemData).then();
    },
    updateItem: () => {
      const updatedItemData = {
        id: 51,
        name: "Eyvaz",
        x: 100,
        y: 100,
        width: 75,
        height: 90
      };
      API.updateItem(updatedItemData).then();
    },
    deleteItem: () => {
      API.deleteItem(51).then();
    }
  }
}
</script>

<style lang="scss">

</style>
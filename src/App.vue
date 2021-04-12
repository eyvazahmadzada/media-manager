<template>
  <div id="app">
    <HelloWorld msg="Welcome to Your Vue.js App"/>
    <button v-on:click="createItem">Create item</button>
    <button v-on:click="updateItem">Update item</button>
    <button v-on:click="deleteItem">Delete item</button>
  </div>
</template>

<script>
import HelloWorld from './components/HelloWorld.vue';
import axios from './axios';

export default {
  name: 'App',
  components: {
    HelloWorld
  },
  mounted: () => {
    axios.get("/get-items.php")
      .then(data => console.log(data))
      .catch(err => console.log(err)); 
  },
  methods: {
    createItem: () => {
      axios.post("/create-item.php", {
        name: "test2",
        x: 15,
        y: 25,
        width: 50,
        height: 100
      }).then();
    },
    updateItem: () => {
      axios.put("/update-item.php", {
        id: 48,
        name: "Eyvaz",
        x: 100,
        y: 100,
        width: 75,
        height: 90
      }).then();
    },
    deleteItem: () => {
      axios.delete("/delete-item.php", { 
        data: { id: 50 } 
      }).then(data => console.log(data));
    }
  }
}
</script>

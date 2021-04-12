<template>
  <div class="home" @mousedown="mouseDown">
    <Item />
  </div>
</template>

<script>
import Item from "../components/Item";
import API from "../config/api";

export default {
  components: { Item },
  name: "Home",
  mounted: () => {
    API.getItems()
      .then(data => console.log(data))
      .catch(err => console.log(err));
  },
  methods: {
    mouseDown: function() {
      // Add window mouse event listeners
      window.addEventListener("mousemove", this.mouseMove);
      window.addEventListener("mouseup", this.mouseUp);
    },
    mouseMove: function() {
      console.log("test");
    },
    mouseUp: function() {
      console.log("test");
    },
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
};
</script>

<!-- Styling with sass -->
<style lang="scss" scoped>
  .home {
    padding: 8px;
  }
</style>

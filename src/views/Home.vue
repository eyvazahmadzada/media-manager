<template>
  <div class="home">
    <div v-for="item in items" :key="item.id">
        <Item 
          @onPropertiesUpdate="updateItem" 
          :id="item.id" 
          :img="item.img" 
          :initialX="item.x" 
          :initialY="item.y" 
          :initialWidth="item.width" 
          :initialHeight="item.height" 
        />
    </div>
    <input 
      name="file"
      class="home-image-input" 
      type="file" 
      accept="image/jpeg, image/jpg, image/png" 
      ref="imageInput"
    >
  </div>
</template>

<script>
import Item from "../components/Item";
import API from "../config/api";

export default {
  components: { Item },
  name: "Home",
  data: function() {
    return {
      initialSize: 100, // Initial size for the items
      errorMsg: "",
      items: null
    }
  },
  mounted: function() {
    // Get items from the server
    API.getItems().then(res => this.items = res.data);

    // A small trick to get access to "this" in listeners
    const _this = this;

    window.addEventListener("mousedown", this.createItem);
    window.addEventListener("keypress", function(e) {
      // If keydown event gets fired, function is executed only if space is pressed
      if(e.code === "Space") {
        // Trigger upload button
        _this.$refs.imageInput.click();

        // Handle image upload
        _this.$refs.imageInput.addEventListener("change", _this.uploadImage);
      }
    });
  },
  methods: {
    // A utility function to generate random positions
    generateRandomPosition: function(maxX, maxY) {
      let x = Math.random() * maxX;
      let y = Math.random() * maxY;

      // Position will be returned only if it's valid
      while(!this.isPositionValid(x, y)) {
        x = Math.random() * maxX;
        y = Math.random() * maxY;
      }
      return { x, y };
    },
    // A utility function to check if position is valid - not inside and too close to another element
    isPositionValid: function(x, y) {
      const size = this.initialSize;
  
      let isValid = true;
      this.items.forEach(item => {
        if( //10 for resizers
          (x > (item.x - size) && x < (item.x + item.width + 10)) &&
          (y > (item.y - size) && y < (item.y + item.height + 10))
        ) {
          isValid = false;
        }
      });
      return isValid;
    },
    uploadImage: function() {
      // A small trick to get access to "this" in if checks
      const _this = this;

      const inputEl = this.$refs.imageInput;

      // If a file is uploaded
      if(inputEl.files[0]) {
        const imageData = new FormData();
        imageData.append("image", inputEl.files[0]);

        // Upload image to server
        API.uploadImage(imageData)
          .then(res => {
            if(!res.data.error) {
              // If image is uploaded, create new item
              _this.createItem(null, inputEl.files[0].name);
            } else {
              _this.errorMsg = res.data.error;
            }
          });
      }
    },
    createItem: function(e, imageName = null) {
      const _this = this;

      let newItem = {}; // Going to be an image or a rectangle

      if(imageName) {
        // Generate a random position for the image to be created
        const randPositions = _this.generateRandomPosition(750, 500);
        newItem.x = randPositions.x;
        newItem.y = randPositions.y;

        // Assign image name to the item
        newItem.img = imageName;
      } else {
        // If position selected by user is not valid, don't create item
        if(!this.isPositionValid(e.clientX, e.clientY)) { return }

        // Assign selected position
        newItem.x = e.clientX;
        newItem.y = e.clientY;

        // No image
        newItem.img = "";
      }

      // Defaults for the item
      newItem.id = Math.random() * 10000;
      newItem.width = this.initialSize;
      newItem.height = this.initialSize;

      // Send new item to server
      API.createItem(newItem).then(res => {
        if(!res.data.error) {
          // Update the items array
          _this.items = [...this.items, newItem];
        } else {
          _this.errorMsg = res.data.error
        }
      });
    },
    updateItem: function(itemProps) {
      const _this = this;

      //Find item index with same id
      const itemIndex = this.items.findIndex(item => item.id === itemProps.id);

      // Update item
      const updatedItem = {...this.items[itemIndex], ...itemProps};

      // Update item in the server
      API.updateItem(updatedItem).then(res => {
        if(!res.data.error) {
          //Update item in the array
          this.items[itemIndex] = {...this.items[itemIndex], ...updatedItem};
        } else {
          _this.errorMsg = res.data.error
        }
      });
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

    &-image-input {
      display: none;
    }
  }
</style>

<template>
  <main class="home">
    <!-- Show and hide errors -->
    <div class="home-error" ref="error"></div>

    <!-- Info -->
    <div class="home-info" ref="info">
      <h2>RULES:</h2>
      <p><b>Upload an image:</b> Press on the <b>SPACE</b> key</p>
      <p><b>Create a rectangle:</b> Click on the screen</p>
      <p><b>Resize an item:</b> Use box resizers</p>
      <p><b>Delete an item:</b> Double click on it</p>
    </div>

    <!-- Render items -->
    <section v-for="item in items" :key="item.id">
      <Item
        @onPropertiesUpdate="updateItem"
        @onDelete="deleteItem"
        :id="item.id"
        :img="item.img"
        :initialX="item.x"
        :initialY="item.y"
        :initialWidth="item.width"
        :initialHeight="item.height"
      />
    </section>
    <input
      name="file"
      class="home-image-input"
      type="file"
      accept="image/jpeg, image/jpg, image/png"
      ref="imageInput"
    />
  </main>
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
      items: null
    };
  },
  mounted: function() {
    // Show info
    const infoEl = this.$refs.info;
    setTimeout(() => infoEl.classList.add("show"));

    // Hide info after some time
    setTimeout(() => infoEl.classList.remove("show"), 4000);

    // Get items from the server
    API.getItems().then((res) => (this.items = res.data));

    window.addEventListener("mousedown", this.createItem);

    // Get access to 'this'
    const _this = this;

    window.addEventListener("keypress", function(e) {
      // If keydown event gets fired, function is executed only if space is pressed
      if (e.code === "Space") {
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
      while (!this.isPositionValid(x, y)) {
        x = Math.random() * maxX;
        y = Math.random() * maxY;
      }
      return { x, y };
    },
    // A utility function to check if position is valid - not inside and too close to another element
    isPositionValid: function(x, y) {
      const size = this.initialSize;

      let isValid = true;
      this.items.forEach((item) => {
        if (
          //10 for resizers
          x > item.x - size &&
          x < item.x + item.width + 10 &&
          y > item.y - size &&
          y < item.y + item.height + 10
        ) {
          isValid = false;
        }
      });
      return isValid;
    },
    // A utility function to show and hide errors
    showMessage: function(errorMsg) {
      const errorEl = this.$refs.error;
      errorEl.innerHTML = errorMsg;
      errorEl.classList.add("show");

      // Hide error after some time
      setTimeout(() => errorEl.classList.remove("show"), 2000);
    },
    uploadImage: function() {
      const inputEl = this.$refs.imageInput;

      // If a file is uploaded
      if (inputEl.files[0]) {
        const imageData = new FormData();
        imageData.append("image", inputEl.files[0]);

        // Upload image to server
        API.uploadImage(imageData).then((res) => {
          if (!res.data.error) {
            // If image is uploaded, create new item
            this.createItem(null, inputEl.files[0].name);
            this.showMessage(res.data.message);
          } else {
            this.showMessage(res.data.error);
          }
        });
      }
    },
    createItem: function(e, imageName = null) {
      let newItem = {}; // Going to be an image or a rectangle

      if (imageName) {
        // Generate a random position for the image to be created
        const randPositions = this.generateRandomPosition(750, 500);
        newItem.x = randPositions.x;
        newItem.y = randPositions.y;
      } else {
        // If position selected by user is not valid, don't create item
        if (!this.isPositionValid(e.clientX, e.clientY)) {
          return;
        }

        // Assign selected position
        newItem.x = e.clientX;
        newItem.y = e.clientY;
      }

      // Defaults for the item
      newItem.id = Math.floor(Math.random() * 10000);
      newItem.width = this.initialSize;
      newItem.height = this.initialSize;
      newItem.img = imageName;

      // Send new item to server
      API.createItem(newItem).then((res) => {
        if (!res.data.error) {
          // Update the items array
          this.items = [...this.items, newItem];
        } else {
          this.showMessage(res.data.message);
        }
      });
    },
    updateItem: function(itemProps) {
      //Find item index with same id
      const itemIndex = this.items.findIndex((item) => item.id === itemProps.id);

      // Update item
      const updatedItem = { ...this.items[itemIndex], ...itemProps };

      // Update item in the server
      API.updateItem(updatedItem).then((res) => {
        if (!res.data.error) {
          //Update item in the array
          this.items[itemIndex] = { ...this.items[itemIndex], ...updatedItem };
        } else {
          this.showMessage(res.data.error);
        }
      });
    },
    deleteItem: function(itemId) {
      API.deleteItem(itemId).then((res) => {
        if (!res.data.error) {
          // Delte item from items array
          this.items = this.items.filter((item) => item.id !== itemId);
          this.showMessage(res.data.message);
        } else {
          this.showMessage(res.data.error);
        }
      });
    }
  }
};
</script>

<!-- Styling with sass -->
<style lang="scss" scoped>
.box {
  position: fixed;
  z-index: 100;
  right: 50%;
  top: -100%;
  width: max-content;
  transform: translate(50%, 0);
  background-color: #eaeaea;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 1px 2px 2px rgba(0, 0, 0, 0.25);
  transition: all 400ms ease-out;

  // Show state
  &.show {
    top: 0;
  }
}

.home {
  padding: 8px;

  &-error {
    @extend .box;
    margin-top: 24px;
  }

  &-info {
    @extend .box;
    text-align: center;
    margin-top: 12px;

    > h2 {
      margin-top: 0;
    }
  }

  &-image-input {
    display: none;
  }
}
</style>

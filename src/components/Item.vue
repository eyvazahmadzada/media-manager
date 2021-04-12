<template>
  <div @mousedown="mouseDown" class="item" ref="item">
    <div v-for="direction in directions" :key="direction">
      <ItemResizer @resizing="setIsResizing" :direction="direction" />
    </div>
  </div>
</template>

<script>
import ItemResizer from './ItemResizer';

export default {
  name: 'Item',
  components: { ItemResizer },
  data: function() {
    return {
      directions: ["top-left", "top-right", "bottom-left", "bottom-right"],
      isResizing: false,
      x: 0,
      y: 0,
      width: 0,
      height: 0
    }
  },
  methods: {
    setIsResizing: function(resizing) {
      this.isResizing = resizing;
    },
    // A Utility function to update item position info
    updatePosition: function(updatedX, updatedY) {
      this.x = updatedX;
      this.y = updatedY;
    },
    mouseDown: function(e) {
      // Add window mouse event listeners
      window.addEventListener('mousemove', this.mouseMove);
      window.addEventListener('mouseup', this.mouseUp);

      this.updatePosition(e.clientX, e.clientY);
    },
    mouseMove: function(e) {
      if(!this.isResizing) {
        // Update item position on mouse move
        this.x -= e.clientX;
        this.y -= e.clientY;

        // Get item element from DOM
        const itemEl = this.$el;

        // Get item element position info
        const rect = itemEl.getBoundingClientRect();

        // Update item position in DOM
        itemEl.style.left = rect.left - this.x + "px";
        itemEl.style.top = rect.top - this.y + "px";

        this.updatePosition(e.clientX, e.clientY);
      }
    },
    mouseUp: function() {
      // Remove window mouse event listeners
      window.removeEventListener("mousemove", this.mouseMove);
      window.removeEventListener("mouseup", this.mouseUp);
    }
  }
}
</script>

<!-- Styling with sass -->
<style lang="scss" scoped>

  //Item styling
  .item {
    position: absolute;
    min-width: 50px;
    min-height: 50px;
    background-color: #E4B645;
  }
</style>


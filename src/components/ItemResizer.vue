<template>
    <span @mousedown="mouseDown" :class="`item-resizer ${direction}`"></span>
</template>

<script>
export default {
  name: 'ItemResizer',
  props: { direction: String },
  data: function() {
    return {
      x: 0,
      y: 0
    }
  },
  methods: {
    setIsResizing: function(resizing) {
      this.$emit("resizing", resizing);
    },
    // A Utility function to update resizer position info
    updatePosition: function(updatedX, updatedY) {
      this.x = updatedX;
      this.y = updatedY;
    },
    // A utility function to update properties of item in DOM
    updateItemProperties: function(item, top, left, width, height) {
      if(width) { item.style.width = width + "px"; }
      if(height) { item.style.height = height + "px"; }
      if(top) { item.style.top = top + "px"; }
      if(left) { item.style.left = left + "px"; }
    },
    mouseDown: function(e) {
      // Send resizing info to item
      this.setIsResizing(true);

      this.updatePosition(e.clientX, e.clientY);

      // Add window mouse event listeners
      window.addEventListener('mousemove', this.mouseMove);
      window.addEventListener('mouseup', this.mouseUp);
    },
    mouseMove: function(e) {
      // Get item element from DOM
      const parent = this.$parent;
      const itemEl = parent.$el;

      // Get item element position info
      const rect = itemEl.getBoundingClientRect();

      // Update item position on resizer move
      switch(this.direction) {
        case "top-left":
          this.updateItemProperties(
            itemEl,
            rect.top - (this.y - e.clientY),
            rect.left - (this.x - e.clientX),
            rect.width + (this.x - e.clientX),
            rect.height + (this.y - e.clientY)
          );
          break;
        case "top-right":
          this.updateItemProperties(
            itemEl,
            rect.top - (this.y - e.clientY),
            null,
            rect.width - (this.x - e.clientX),
            rect.height + (this.y - e.clientY)
          );
          break;
        case "bottom-left":
          this.updateItemProperties(
            itemEl,
            null,
            rect.left - (this.x - e.clientX),
            rect.width + (this.x - e.clientX),
            rect.height - (this.y - e.clientY)
          );
          break;
        case "bottom-right":
          this.updateItemProperties(
            itemEl,
            null,
            null,
            rect.width - (this.x - e.clientX),
            rect.height - (this.y - e.clientY)
          );
          break;
        default: 
          return;
      }

      this.updatePosition(e.clientX, e.clientY);
    },
    mouseUp: function() {
      // Remove window mouse event listeners
      window.removeEventListener("mousemove", this.mouseMove);
      window.removeEventListener("mouseup", this.mouseUp);

      // Send resizing info to item
      this.setIsResizing(false);
    },
  }
}
</script>

<!-- Styling with sass -->
<style lang="scss" scoped>

  // Resizers for changing item size
  .item-resizer {
    position: absolute;
    width: 10px;
    height: 10px;
    background-color: #748EC4;
    border-radius: 50%;
    z-index: 10;

    &.top-left { 
      cursor: nw-resize;
      top: -3px;
      left: -3px; 
    }
    &.top-right {
      cursor: ne-resize;
      top: -3px;
      right: -3px; 
    }

    &.bottom-left { 
      cursor: sw-resize;
      bottom: -3px;
      left: -3px; 
    }
    &.bottom-right { 
      cursor: se-resize;
      bottom: -3px;
      right: -3px; 
    }
  }
</style>


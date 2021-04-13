<template>
    <span @mousedown="mouseDown" :class="`item-resizer ${direction}`"></span>
</template>

<script>
export default {
  name: 'ItemResizer',
  props: { direction: String },
  data: function() {
    return {
      clientX: 0,
      clientY: 0
    }
  },
  methods: {
    setIsResizing: function(resizing) {
      this.$emit("resizing", resizing);
    },
    // Send updated info to parent
    updateProperties: function(left, top, width, height) {
      this.$emit("onPropertiesUpdate", left, top, width, height);
    },
    // A Utility function to update resizer position info
    setClientPosition: function(x, y) {
      this.clientX = x;
      this.clientY = y;
    },
    mouseDown: function(e) {
      // Send resizing info to item
      this.setIsResizing(true);

      this.setClientPosition(e.clientX, e.clientY);

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
          this.updateProperties(
            rect.left - (this.clientX - e.clientX),
            rect.top - (this.clientY - e.clientY),
            rect.width + (this.clientX - e.clientX),
            rect.height + (this.clientY - e.clientY)
          );
          break;
        case "top-right":
          this.updateProperties(
            null,
            rect.top - (this.clientY - e.clientY),
            rect.width - (this.clientX - e.clientX),
            rect.height + (this.clientY - e.clientY)
          );
          break;
        case "bottom-left":
          this.updateProperties(
            rect.left - (this.clientX - e.clientX),
            null,
            rect.width + (this.clientX - e.clientX),
            rect.height - (this.clientY - e.clientY)
          );
          break;
        case "bottom-right":
          this.updateProperties(
            null,
            null,
            rect.width - (this.clientX - e.clientX),
            rect.height - (this.clientY - e.clientY)
          );
          break;
        default: 
          return;
      }

      this.setClientPosition(e.clientX, e.clientY);
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


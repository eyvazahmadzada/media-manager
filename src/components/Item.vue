<template>
  <div @mousedown="mouseDown" class="item">
    <div v-for="direction in directions" :key="direction">
      <ItemResizer
        @resizing="setIsResizing"
        @onPropertiesUpdate="updateProperties"
        :direction="direction"
      />
    </div>
  </div>
</template>

<script>
import ItemResizer from "./ItemResizer";

export default {
  name: "Item",
  props: {
    id: Number,
    img: String,
    initialX: Number,
    initialY: Number,
    initialWidth: Number,
    initialHeight: Number
  },
  components: { ItemResizer },
  data: function() {
    return {
      directions: ["top-left", "top-right", "bottom-left", "bottom-right"],
      isResizing: false,
      x: this.initialX,
      y: this.initialY,
      width: this.initialWidth,
      height: this.initialHeight,
      clientX: 0,
      clientY: 0
    };
  },
  mounted: function() {
    // Dynamically add image or background color
    this.$el.style.background = this.img ? `url('${window.location}img/${this.img}')` : "#E4B645";
    this.updateProperties(this.x, this.y, this.width, this.height);

    // Delete item on double click
    this.$el.addEventListener("dblclick", () => this.$emit("onDelete", this.id));
  },
  methods: {
    setIsResizing: function(resizing) {
      this.isResizing = resizing;
    },
    // A Utility function to update item position info
    setClientPosition: function(x, y) {
      this.clientX = x;
      this.clientY = y;
    },
    // A utility function to update properties of item
    updateProperties: function(left, top, width, height) {
      if (left) {
        this.x = left;
        this.$el.style.left = left + "px";
      }
      if (top) {
        this.y = top;
        this.$el.style.top = top + "px";
      }
      if (width) {
        this.width = width;
        this.$el.style.width = width + "px";
      }
      if (height) {
        this.height = height;
        this.$el.style.height = height + "px";
      }
    },
    mouseDown: function(e) {
      // Add window mouse event listeners
      window.addEventListener("mousemove", this.mouseMove);
      window.addEventListener("mouseup", this.mouseUp);

      this.setClientPosition(e.clientX, e.clientY);
    },
    mouseMove: function(e) {
      if (!this.isResizing) {
        // Update item position on mouse move
        this.clientX -= e.clientX;
        this.clientY -= e.clientY;

        // Get item element position info
        const rect = this.$el.getBoundingClientRect();

        this.updateProperties(rect.left - this.clientX, rect.top - this.clientY, null, null);

        this.setClientPosition(e.clientX, e.clientY);
      }
    },
    mouseUp: function() {
      // Remove window mouse event listeners
      window.removeEventListener("mousemove", this.mouseMove);
      window.removeEventListener("mouseup", this.mouseUp);

      // Send item properties to parent
      this.$emit("onPropertiesUpdate", {
        id: this.id,
        x: this.x,
        y: this.y,
        width: this.width,
        height: this.height
      });
    }
  }
};
</script>

<!-- Styling with sass -->
<style lang="scss" scoped>
//Item styling
.item {
  position: absolute;
  min-width: 50px;
  min-height: 50px;
  background-repeat: no-repeat !important;
  background-size: 100% 100% !important;
}
</style>

<template>
  <div ref="slider">
     <img v-for="image in images" :src="$cloudinary.url(image.image)" />
  </div>
</template>

<script>
export default {
  props: {
    images: {
      type: Array
    }
  },

  mounted() {
  }
}
</script>
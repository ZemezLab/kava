<template>
  <div class="swiper-container" ref="container">
    <div class="swiper-wrapper">
      <div class="swiper-slide" v-for="image in images" :style="{ 'background-image': 'url(' + $cloudinary.url(image.image, { height: height, crop: 'fit' }) +')' } ">

      </div>
    </div>
    <div class="swiper-pagination"></div>

    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>

    <div class="swiper-scrollbar"></div>
  </div>
</template>

<script>
import Swiper from 'swiper/bundle';

export default {
  props: {
    images: {
      type: Array
    },
    height: {
      type: Number,
      default: 500
    }
  },

  mounted() {
    jQuery(this.$refs.container).height(this.height);

    new Swiper('.swiper-container', {
      slidesPerView: 3,
      spaceBetween: 15,
      freeMode: true,

      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },

      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
    });
  }
}
</script>
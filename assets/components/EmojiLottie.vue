<template>
  <div
      class="emoji-container"
      ref="container"
      @click="$emit('click')"
      @mouseenter="isSelected && play()"
      @mouseleave="isSelected && pause()"
      :style="{
    filter: isSelected ? 'none' : 'grayscale(100%)',
    cursor: 'pointer',
    margin: '0 auto'
  }"
  />

</template>

<script>
import lottie from 'lottie-web'

export default {
  props: {
    animationData: Object,
    isSelected: Boolean,
  },
  mounted() {
    this.anim = lottie.loadAnimation({
      container: this.$refs.container,
      renderer: 'svg',
      loop: true,
      autoplay: false,
      animationData: this.animationData,
    })
  },
  methods: {
    play() {
      this.anim.play()
    },
    pause() {
      if (!this.isSelected) {
        this.anim.pause()
      }
    }
  },
  watch: {
    isSelected(newVal) {
      if (newVal) {
        this.anim.play()
      } else {
        this.anim.pause()
      }
    }
  },
  beforeUnmount() {
    this.anim.destroy()
  },
}
</script>

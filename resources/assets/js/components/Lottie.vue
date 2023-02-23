<template>
  <LottieAnimation ref="anim" :animationData="loader" :loop="true" speed="30" />
</template>

<script>

import loader from "../../../../public/img/workhy_logo.json"
export default {
  data() {
    return {
      loader
    }
  },
  name: "Lottie",
};
</script>
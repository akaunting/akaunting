<template>
    <div v-if="video || screenshots">
        <el-carousel :height="height"
            :initial-index="initial_index"
            :trigger="trigger" :autoplay="autoplay"
            :indicator-position="indicator_position"
            :type="type" :loop="loop" :direction="direction"
            :interval="interval" :arrow="arrow">

            <el-carousel-item v-if="video">
                <iframe class="carousel-frame w-100" height="365px"
                :src="'https://www.youtube-nocookie.com/embed/' + video"
                frameborder="0"
                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                <div class="carousel-description py-2">
                    {{ name }}
                </div>
            </el-carousel-item>

            <el-carousel-item v-for="(screenshot, index) in screenshots" :key="index">
                <img @click="openGallery(index)" class="d-block w-100 carousel-frame" height="365px" :src="screenshot.path_string" :alt="screenshot.alt_attribute">
                <div class="carousel-description py-2" v-if="screenshot.description">
                    {{ screenshot.description }}
                </div>
                <div class="carousel-description py-2" v-else>
                    {{ name }}
                </div>
            </el-carousel-item>
        </el-carousel>

      <LightBox
        v-if="media.length"
        ref="lightbox"
        :media="media"
        :show-caption="true"
        :show-light-box="false"
      />
    </div>
</template>

<script>
import Vue from 'vue';
import {Image, Carousel, CarouselItem} from 'element-ui';

import LightBox from 'vue-image-lightbox';
import 'vue-image-lightbox/dist/vue-image-lightbox.min.css';
import VueLazyLoad from 'vue-lazyload';

Vue.use(VueLazyLoad);

export default {
    name: "akaunting-carousel",

    components: {
        [Image.name]: Image,
        [Carousel.name]: Carousel,
        [CarouselItem.name]: CarouselItem,
        LightBox
    },

    props: {
        name: {
            type: String,
            default: null,
            description: "App Name"
        },
        video: {
            type: String,
            default: null,
            description: "App Video"
        },
        screenshots: {
            type: Array,
            default: false,
            description: "App Screenshots"
        },
        height: {
            type: String,
            default: null,
            description: "height of the carousel"
        },
        initial_index: {
            type: Number,
            default: 0,
            description: "index of the initially active slide (starting from 0)"
        },
        trigger: {
            type: String,
            default: 'hover',
            description: "how indicators are triggered (hover/click)"
        },
        autoplay: {
            type: Boolean,
            default: false,
            description: "whether automatically loop the slides"
        },
        interval: {
            type: Number,
            default: 3000,
            description: "interval of the auto loop, in milliseconds"
        },
        indicator_position: {
            type: String,
            default: 'none',
            description: "position of the indicators (outside/none)"
        },
        arrow: {
            type: String,
            default: 'hover',
            description: "when arrows are shown (always/hover/never)"
        },
        type: {
            type: String,
            default: '',
            description: "type of the Carousel (card)"
        },
        loop: {
            type: Boolean,
            default: true,
            description: "display the items in loop"
        },
        direction: {
            type: String,
            default: 'horizontal',
            description: "display direction (horizontal/vertical)"
        }
    },

    mounted() {
        let media = [];

        if (this.screenshots.length) {
            let name = this.name;

            this.screenshots.forEach(function(screenshot) {
                media.push({ // For image
                    thumb: screenshot.path_string,
                    src: screenshot.path_string,
                    caption: (screenshot.description.length) ? screenshot.description : name,
                });
            });
        }

        this.media = media;
    },

    data: function () {
        return {
            media: [],
        }
    },

    methods: {
        openGallery(index) {
            this.$refs.lightbox.showImage(index)
        }
    }
}
</script>

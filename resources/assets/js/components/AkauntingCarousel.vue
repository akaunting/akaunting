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
                <img class="d-block w-100 carousel-frame" height="365px" :src="screenshot.path_string" :alt="screenshot.alt_attribute">
                <div class="carousel-description py-2">
                    {{ screenshot.description }}
                </div>
            </el-carousel-item>
        </el-carousel>
    </div>
</template>

<script>
import {Image, Carousel, CarouselItem} from 'element-ui';

export default {
    name: "akaunting-carousel",

    components: {
        [Image.name]: Image,
        [Carousel.name]: Carousel,
        [CarouselItem.name]: CarouselItem,
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
            default: 1,
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
    }
}
</script>

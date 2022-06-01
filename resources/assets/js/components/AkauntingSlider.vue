<template>
    <div v-if="video || screenshots">
      <div class="swiper-carousel overflow-hidden">
        <div class="swiper-wrapper" style="flex-wrap: nowrap !important;">
          <div class="swiper-slide" v-for="(screenshot, index) in screenshots" :key="index">
            <a :href="screenshot.path_string" class="glightbox">
                <img class="rounded-lg object-cover cursor-pointer" :src="screenshot.path_string" :alt="screenshot.alt_attribute" />
                <div class="text-gray-700 text-sm my-2">{{ screenshot.description }}</div>
            </a>
          </div>
        </div>
        <div v-if="pagination" class="swiper-pagination w-full flex justify-center gap-1"></div>

        <div v-if="arrow" class="swiper-button-next ltr:-right-8 rtl:-left-8 top-12">
            <span class="material-icons text-5xl">chevron_right</span>
        </div>

        <div v-if="arrow" class="swiper-button-prev ltr:-left-8 rtl:-right-8 top-12">
            <span class="material-icons text-5xl">chevron_left</span>
        </div>
      </div>
    </div>
</template>

<script>
import Swiper, { Navigation, Pagination } from 'swiper';
Swiper.use([Navigation, Pagination]);

import GLightbox from 'glightbox';
import 'glightbox/dist/css/glightbox.min.css';

export default {
    name: "akaunting-slider",

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
        arrow: {
            type: [Boolean, String],
            default: false,
        },
        pagination: {
            type: [Boolean, String],
            default: false,
        },
        type: {
            type: String,
            default: '',
            description: "type of the Carousel (card)"
        },
        sliderView: {
            type: [Number, String],
            default: 4,
        },
        sliderRow: {
            type: [Number, String],
            default: 2,
        }
    },
    mounted() {
        let media = [];
        if (this.screenshots.length) {
            this.screenshots.forEach(function(screenshot) {
                media.push({ // For image
                    thumb: screenshot.path_string,
                    src: screenshot.path_string,
                });
            });
        }
        this.media = media;
        new Swiper(".swiper-carousel", {
            loop: false,
            grid: {
                rows: this.sliderRow,
            },
            spaceBetween: 30,
            breakpoints: {
                640: {
                    slidesPerView: 1
                },
                768: {
                    slidesPerView: this.sliderView,
                }
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true
              },
              navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
              },
        });
        GLightbox({
            touchNavigation: true,
            loop: false,
            autoplayVideos: false,
            selector: ".glightbox"
        });
    },
    data: function () {
        return {
            media: [],
        }
    },
}
</script>

<template>
  <div :id="id" class="world-map"></div>
</template>
<script>
import 'd3';
import * as d3 from 'd3';
import 'topojson';
import { throttle } from '@/util/throttle';

export default {
  name: 'world-map',
  props: {
    mapData: {
      type: Object,
      default: () => ({})
    },
    points: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      id: this.randomString(),
      color1: '#f6f9fc',
      color2: '#adb5bd',
      highlightFillColor: '#ced4da',
      borderColor: 'white',
      highlightBorderColor: 'white',
      bubbleHighlightFillColor: '#11cdef',
      bubbleFillColor: '#fb6340'
    };
  },
  methods: {
    generateColors(length) {
      return d3
        .scaleLinear()
        .domain([0, length])
        .range([this.color1, this.color2]);
    },
    generateMapColors() {
      let mapDataValues = Object.values(this.mapData);
      let maxVal = Math.max(...mapDataValues);
      let colors = this.generateColors(maxVal);
      let mapData = {};
      let fills = {
        defaultFill: '#EDF0F2'
      };
      for (let key in this.mapData) {
        let val = this.mapData[key];
        fills[key] = colors(val);
        mapData[key] = {
          fillKey: key,
          value: val
        };
      }
      return {
        mapData,
        fills
      };
    },
    async initVectorMap() {
      let DataMap = await import('datamaps');
      DataMap = DataMap.default || DataMap
      let { fills, mapData } = this.generateMapColors();
      let worldMap = new DataMap({
        scope: 'world',
        element: document.getElementById(this.id),
        fills,
        data: mapData,
        responsive: true,
        geographyConfig: {
          borderColor: this.borderColor,
          borderWidth: 1,
          borderOpacity: 1,
          highlightFillColor: this.highlightFillColor,
          highlightBorderColor: this.highlightBorderColor,
          highlightBorderWidth: 1,
          highlightBorderOpacity: 1
        }
      });
      let bubbleOptions = {
        radius: 2,
        borderWidth: 4,
        highlightBorderWidth: 4,
        fillKey: this.bubbleFillColor,
        fillColor: this.bubbleFillColor,
        borderColor: this.bubbleFillColor,
        highlightFillColor: this.bubbleHighlightFillColor,
        highlightBorderColor: this.bubbleHighlightFillColor
      }
      let bubblePoints = this.points.map(point => {
        return {
          ...bubbleOptions,
          ...point
        }
      })
      worldMap.bubbles(bubblePoints, {
        popupTemplate: function(geo, data) {
          return '<div class="hoverinfo">' + data.name
        }
      });
      let resizeFunc = worldMap.resize.bind(worldMap);
      window.addEventListener(
        'resize',
        () => {
          throttle(resizeFunc, 40);
        },
        false
      );
    },
    randomString() {
      let text = "";
      let possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

      for (let i = 0; i < 5; i++)
        text += possible.charAt(Math.floor(Math.random() * possible.length));

      return text;
    }
  },
  async mounted() {
    this.initVectorMap();
  }
};
</script>
<style></style>

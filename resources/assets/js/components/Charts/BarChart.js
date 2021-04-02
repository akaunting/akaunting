import { Bar, mixins } from 'vue-chartjs';
import globalOptionsMixin from "@/components/Charts/globalOptionsMixin";

export default {
  name: 'bar-chart',
  extends: Bar,
  mixins: [mixins.reactiveProp, globalOptionsMixin],
  props: {
    extraOptions: {
      type: Object,
      default: () => ({})
    }
  },
  data() {
    return {
      ctx: null
    };
  },
  mounted() {
    this.$watch(
      'chartData',
      (newVal, oldVal) => {
        if (!oldVal) {
          this.renderChart(this.chartData, this.extraOptions);
        }
      },
      { immediate: true }
    );
  }
};

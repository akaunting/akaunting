import Chart from 'chart.js';
import { initGlobalOptions } from "./../../components/Charts/config";
export default {
  mounted() {
    initGlobalOptions(Chart);
  }
}

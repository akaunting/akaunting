<template>
    <div>
        <section class="flex text-xl justify-center content-center">
            <div class="days mr-2 relative"> {{ displayDays}} </div>
            <span class="leading-snug">:</span>

            <div class="hours mx-2 relative"> {{ displayHours }} </div>
            <span class="leading-snug">:</span>

            <div class="minutes mx-2 relative"> {{ displayMinutes }} </div>
            <span class="leading-snug">:</span>

            <div class="seconds ml-2 relative"> {{ displaySeconds }} </div>
        </section>
    </div>
</template>

<script>

export default {
    name: 'akaunting-countdown',

    props: {
        textDays: {
            type: String,
            default: 'days',
            description: "Modal header title"
        },

        year: {
            type: Number,
            default: 0,
            description: "Modal header title"
        },
        month: {
            type: Number,
            default: 0,
            description: "Modal header title"
        },
        date: {
            type: Number,
            default: 0,
            description: "Input readonly status"
        },
        hour: {
            type: Number,
            default: 0,
            description: "Input disabled status"
        },
        minute: {
            type: Number,
            default: 0,
            description: "Input value defalut"
        },
        second: {
            type: Number,
            default: 0,
            description: "Input model defalut"
        },
        millisecond: {
            type: Number,
            default: 0,
            description: "Prepend icon (left)"
        }
    },

    data:() => ({
        displayDays: 0,
        displayHours: 0,
        displayMinutes: 0,
        displaySeconds: 0,
        loaded: false,
        expired: false
    }),

    computed: {
        _seconds: () => 1000,

        _minutes() {
            return this._seconds * 60;
        },

        _hours() {
            return this._minutes * 60;
        },

        _days() {
            return this._hours * 24;
        },

        end() {
            return new Date(
                this.year,
                this.month,
                this.date,
                this.hour,
                this.minute,
                this.second,
                this.millisecond
            );
        }
    },

    mounted() {
        this.showRemainig();
    },

    methods: {
        formatNum: num =>(num < 10 ? '0' + num : num),

        showRemainig() {
            const timer = setInterval(() => {
                const now = new Date();

                const distance = this.end.getTime() - now.getTime();

                if (distance < 0) {
                    clearInterval(timer);

                    this.expired = true;

                    return;
                }

                const days = Math.floor(distance / this._days);
                const hours = Math.floor((distance % this._days) / this._hours);
                const minutes = Math.floor((distance % this._hours) / this._minutes);
                const seconds = Math.floor((distance % this._minutes) / this._seconds);

                this.displayMinutes = this.formatNum(minutes);
                this.displaySeconds = this.formatNum(seconds);
                this.displayHours = this.formatNum(hours);
                this.displayDays = this.formatNum(days);

                this.loaded = true;
            }, 1000);
        }
    }
}

</script>

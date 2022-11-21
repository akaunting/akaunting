import * as Sentry from "@sentry/vue";
import { BrowserTracing } from "@sentry/tracing";

export default {
    install(Vue) {
        alert(exception_tracker.params.traces_sample_rate);

        Sentry.init({
            Vue,
            dsn: exception_tracker.action,
            logErrors: true,
            integrations: [
                new BrowserTracing({
                    tracingOrigins: [],
                }),
            ],
            // Set tracesSampleRate to 1.0 to capture 100%
            // of transactions for performance monitoring.
            // We recommend adjusting this value in production
            tracesSampleRate: exception_tracker.params.traces_sample_rate,
        });

        Sentry.setUser({
            id: exception_tracker.user.id,
            username: exception_tracker.user.name,
            email: exception_tracker.user.email,
            ip_address: exception_tracker.ip,
        });

        for (const [key, value] of Object.entries(exception_tracker.tags)) {
            Sentry.setTag(key, value);
        }
    }
}

import * as Sentry from "@sentry/vue";
import { BrowserTracing } from "@sentry/tracing";

export default {
    install(Vue) {
        Sentry.init({
            Vue,
            dsn: exception_tracker.action,

            logErrors: true,

            integrations: [
                new BrowserTracing({
                    tracingOrigins: [],
                }),
                new Sentry.Replay()
            ],
            // Set tracesSampleRate to 1.0 to capture 100%
            // of transactions for performance monitoring.
            // We recommend adjusting this value in production
            tracesSampleRate: exception_tracker.params.traces_sample_rate,

            // This sets the sample rate to be 10%. You may want this to be 100% while
            // in development and sample at a lower rate in production
            replaysSessionSampleRate: 0.1,

            // If the entire session is not sampled, use the below sample rate to sample
            // sessions when an error occurs.
            replaysOnErrorSampleRate: 1.0,
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

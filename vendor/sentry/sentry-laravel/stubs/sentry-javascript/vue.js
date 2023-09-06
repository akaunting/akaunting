import * as Sentry from "@sentry/vue";

Sentry.init({
  dsn: import.meta.env.VITE_SENTRY_DSN_PUBLIC,
});
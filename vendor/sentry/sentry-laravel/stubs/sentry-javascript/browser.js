import * as Sentry from "@sentry/browser";

Sentry.init({
  dsn: import.meta.env.VITE_SENTRY_DSN_PUBLIC,
});
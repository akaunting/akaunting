import * as Sentry from "@sentry/angular-ivy";

Sentry.init({
  dsn: import.meta.env.VITE_SENTRY_DSN_PUBLIC,
});
# Upgrade to 2.0

## Made the `$event` parameter of `EventManager::getListeners()` mandatory

When calling `EventManager::getListeners()` you need to specify the event that
you want to fetch the listeners for. Call `getAllListeners()` instead if you
want to access the listeners of all events.

# Upgrade to 1.2

## Deprecated calling `EventManager::getListeners()` without an event name

When calling `EventManager::getListeners()` without an event name, all
listeners were returned, keyed by event name. A new method `getAllListeners()`
has been added to provide this functionality. It should be used instead.

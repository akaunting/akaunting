# How to contribute
We welcome everyone to submit pull requests with:
- fixes for issues
- change suggestions
- updating of documentation

However, not every pull request will automatically be accepted. I will review each carefully to make sure it is in line with
 the direction I want the package to continue in. This might mean that some pull requests are not accepted, or might stay
 unmerged until a place for them can be determined.

## Testing
- [ ] After making your changes, make sure the tests still pass.
- [ ] When adding new functionality, also add new tests.
- [ ] When fixing errors write and satisfy new unit tests that replicate the issue.
- [ ] Make sure there are no build errors on our CI server (https://ci.genealabs.com/build-status/view/8)
- [ ] All code must past PHPCS and PHPMD PSR2 validation.

## Submitting changes
When submitting a pull request, it is important to make sure to complete the following:
- [ ] Add a descriptive header that explains in a single sentence what problem the PR solves.
- [ ] Add a detailed description with animated screen-grab GIFs visualizing how it works.
- [ ] Explain why you think it should be implemented one way vs. another, highlight performance improvements, etc.

## Coding conventions
Start reading our code and you'll get the hang of it. We optimize for readability:
- indent using four spaces (soft tabs)
- use Blade for all views
- avoid logic in views, put it in controllers or service classes
- ALWAYS put spaces after list items and method parameters (`[1, 2, 3]`, not `[1,2,3]`), around operators (`x += 1`, not `x+=1`), and around hash arrows.
- this is open source software. Consider the people who will read your code, and make it look nice for them. It's sort of like driving a car: Perhaps you love doing donuts when you're alone, but with passengers the goal is to make the ride as smooth as possible.
- emphasis readability of code over patterns to reduce mental debt
- always add an empty line around structures (if statements, loops, etc.)

Thanks!
Mike Bronner, GeneaLabs

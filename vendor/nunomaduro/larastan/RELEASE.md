# Release process

Upon releasing a new version there's some checks and updates to be made:

- Clear your local repository with: `git add . && git reset --hard && git checkout master`
- Check the contents on `https://github.com/nunomaduro/larastan/compare/{latest_version}...master`
  and update the [changelog](/CHANGELOG.md) file with the modifications on this release
  > Note: make sure that there are no breaking changes and you may use `git tag --list` to check the latest release
- Commit the `CHANGELOG.md` with the message: `git commit -m "docs: bumps version to {new_version}"`
- `git push`
- `git tag {new_version}`
- `git push --tags`
- Create a release on GitHub https://github.com/nunomaduro/larastan/releases
- Make a tweet about the release attributing credits to the external collaborators

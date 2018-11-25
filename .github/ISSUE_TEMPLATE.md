Please, submit only real issues. Use the forum for support, feature requests, proposals, new versions, help etc. https://akaunting.com/forum

### Steps to reproduce the issue



### Expected result



### Actual result



### System information (Akaunting, PHP versions)



### Additional comments

This branch cannot be rebased due to conflicts
Rebasing the commits of this branch on top of the base branch cannot be performed automatically due to conflicts encountered while reapplying the individual commits from the head branch.
Rebase and merge  You can also open this in GitHub Desktop or view command line instructions.
Merging via command line
If you do not want to use the merge button or an automatic merge cannot be performed, you can perform a manual merge on the command line.
HTTPS
Git
Patch
	
Step 1: From your project repository, check out a new branch and test the changes.
 git checkout -b akaunting-master 1.3-dev
git pull https://github.com/akaunting/akaunting.git master
Step 2: Merge the changes and update on GitHub.
 git checkout 1.3-dev
git merge --no-ff akaunting-master
git push origin 1.3-dev

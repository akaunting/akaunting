# 3.17.08.08

## Fixes

  * `llk/parser` Use current token if no error token. (Kirill Nesmeyanov, 2017-08-08T09:35:08+02:00)
# 3.17.01.10

  * Quality: Fix CS. (Ivan Enderlin, 2017-01-10T10:25:16+01:00)
  * Quality: Happy new year! (Ivan Enderlin, 2017-01-09T15:21:06+01:00)
  * Test: Add the `Decorrelated` interface. (Ivan Enderlin, 2016-10-25T07:49:56+02:00)
  * Documentation: Add a Research papers Section. (Ivan Enderlin, 2016-10-24T16:07:32+02:00)

# 3.16.10.24

  * Documentation: New `README.md` file. (Ivan Enderlin, 2016-10-17T20:49:41+02:00)
  * Documentation: Fix `docs` and `source` links. (Ivan Enderlin, 2016-10-05T20:22:48+02:00)
  * Documentation: Update `support` properties. (Ivan Enderlin, 2016-10-05T15:54:31+02:00)
  * Documentation: Add the Research papers Section. (Ivan Enderlin, 2016-08-15T14:13:30+02:00)
  * Documentation: Update possibly generated data. (Ivan Enderlin, 2016-08-15T14:05:42+02:00)

# 3.16.08.15

  * Quality: Run `hoa devtools:cs`. (Ivan Enderlin, 2016-08-15T11:23:34+02:00)
  * Documentation: Refresh some phrasings. (Ivan Enderlin, 2016-08-15T11:12:55+02:00)
  * Documentation: Update API documentation. (Ivan Enderlin, 2016-08-15T11:06:36+02:00)
  * Test: Write `Hoa\Compiler\Llk\Llk` test suite. (Ivan Enderlin, 2016-08-14T17:40:38+02:00)
  * Llk: Update exception messages. (Ivan Enderlin, 2016-08-14T18:00:02+02:00)
  * Llk: PP parser only accepts horizontal spaces… (Ivan Enderlin, 2016-08-14T17:42:07+02:00)
  * Llk: Correctly order and merge skip tokens. (Ivan Enderlin, 2016-08-14T17:24:52+02:00)
  * Llk: The whole class must be abstract. (Ivan Enderlin, 2016-08-14T17:24:24+02:00)
  * Test: Write `…r\Llk\Sampler\Exception` test suite. (Ivan Enderlin, 2016-08-14T16:45:12+02:00)
  * Test: Write `…ption\UnrecognizedToken` test suite. (Ivan Enderlin, 2016-08-14T16:43:06+02:00)
  * Test: Write `…ception\UnexpectedToken` test suite. (Ivan Enderlin, 2016-08-14T16:42:52+02:00)
  * Test: Write `…Compiler\Exception\Rule` test suite. (Ivan Enderlin, 2016-08-14T16:42:43+02:00)
  * Test: Write `…ompiler\Exception\Lexer` test suite. (Ivan Enderlin, 2016-08-14T16:42:31+02:00)
  * Test: Write `…\Exception\IllegalToken` test suite. (Ivan Enderlin, 2016-08-14T16:42:07+02:00)
  * Test: Write `…lStateHasNotBeenReached` test suite. (Ivan Enderlin, 2016-08-14T16:41:49+02:00)
  * Test: Write `…ler\Exception\Exception` test suite. (Ivan Enderlin, 2016-08-14T16:41:19+02:00)
  * Test: Write `…piler\Llk\Rule\Analyzer` test suite. (Ivan Enderlin, 2016-08-14T13:18:39+02:00)
  * Analyzer: Fix current rule name. (Ivan Enderlin, 2016-08-14T16:27:20+02:00)
  * Analyzer: More detailed exception messages. (Ivan Enderlin, 2016-08-14T16:27:01+02:00)
  * PP: Sync `node` token with the analyzer. (Ivan Enderlin, 2016-08-14T16:26:34+02:00)
  * Rule: Fix an exception message in the analyzer. (Ivan Enderlin, 2016-08-12T18:04:39+02:00)
  * Rule: Update API documentation. (Ivan Enderlin, 2016-08-12T18:04:30+02:00)
  * PP: A named token can no longer be unified. (Ivan Enderlin, 2016-08-12T18:03:48+02:00)
  * Quality: Fix CS. (Ivan Enderlin, 2016-08-12T17:08:59+02:00)
  * Test: Write `…ler\Llk\Rule\Invocation` test suite. (Ivan Enderlin, 2016-08-12T17:06:15+02:00)
  * Test: Add test case for the `isInfinite` method. (Ivan Enderlin, 2016-08-12T08:06:15+02:00)
  * Rule: Restore infinite max in a repetition. (Ivan Enderlin, 2016-08-12T08:05:19+02:00)
  * Quality: Fix CS and API documentation. (Ivan Enderlin, 2016-08-12T07:55:24+02:00)
  * Rule: Cast and bound min and max in a repetition. (Ivan Enderlin, 2016-08-12T07:54:43+02:00)
  * Test: Write `…ler\Llk\Rule\Repetition` test suite. (Ivan Enderlin, 2016-08-12T07:51:48+02:00)
  * Test: Write `…\Compiler\Llk\Rule\Rule` test suite. (Ivan Enderlin, 2016-08-09T08:54:20+02:00)
  * Test: Write `…Compiler\Llk\Rule\Token` test suite. (Ivan Enderlin, 2016-08-09T08:19:28+02:00)
  * TreeNode: Value default value must be `null`. (Ivan Enderlin, 2016-08-09T08:02:43+02:00)
  * TreeNode: Avoid undefined child access. (Ivan Enderlin, 2016-08-08T17:32:09+02:00)
  * TreeNode: Avoid undefined token value access. (Ivan Enderlin, 2016-08-08T17:31:28+02:00)
  * TreeNode: Force the value to be an array. (Ivan Enderlin, 2016-08-08T17:31:12+02:00)
  * Test: JSON soundness test suite has changed. (Ivan Enderlin, 2016-08-08T17:30:20+02:00)
  * Test: Use `::class` instead of string classnames. (Ivan Enderlin, 2016-08-08T17:25:28+02:00)
  * Test: Fix namespaces. (Ivan Enderlin, 2016-08-08T17:25:16+02:00)
  * Test: Write `…Compiler\Llk\Rule\Entry` test suite. (Ivan Enderlin, 2016-08-08T17:22:26+02:00)
  * Test: Write `…Compiler\Llk\Rule\Ekzit` test suite. (Ivan Enderlin, 2016-08-08T17:22:03+02:00)
  * Test: Write `…\Llk\Rule\Concatenation` test suite. (Ivan Enderlin, 2016-08-08T17:21:45+02:00)
  * Test: Write `…ompiler\Llk\Rule\Choice` test suite. (Ivan Enderlin, 2016-08-08T17:21:07+02:00)
  * Test: Write `…a\Compiler\Llk\TreeNode` test suite. (Ivan Enderlin, 2016-08-08T17:14:07+02:00)
  * Test: Update test cases about Unicode support. (Ivan Enderlin, 2016-08-08T08:14:04+02:00)
  * Test: Documentations are integration test suites. (Ivan Enderlin, 2016-08-08T08:03:11+02:00)
  * Test: Soundness is an integration test suite. (Ivan Enderlin, 2016-08-08T08:01:45+02:00)
  * Test: Write `Hoa\Compiler\Llk\Lexer` test suite. (Ivan Enderlin, 2016-07-15T19:37:06+02:00)
  * Test: Fix test suite name. (Ivan Enderlin, 2016-07-15T19:36:55+02:00)
  * Documentation: Update API and exception message. (Ivan Enderlin, 2016-07-15T19:36:40+02:00)
  * Rule: Use `is_int` to detect if transitional. (Ivan Enderlin, 2016-02-22T12:08:14+01:00)
  * Parser: Remove calls to `getCurrentToken` method. (Ivan Enderlin, 2016-02-22T11:58:19+01:00)
  * Parser: Cut backtrack if k is reached. (Ivan Enderlin, 2016-02-22T11:28:54+01:00)
  * Parser: Simplify a return condition. (Ivan Enderlin, 2016-02-22T11:07:57+01:00)
  * Llk: Save pragmas when saving the parser. (Ivan Enderlin, 2016-02-22T10:24:16+01:00)
  * Llk: Add the `parser.lookahead` pragma. (Ivan Enderlin, 2016-02-22T10:23:37+01:00)
  * Llk: Change pragma `unicode` for `lexer.unicode`. (Ivan Enderlin, 2016-02-22T09:35:39+01:00)
  * Llk: Implement pragmas. (Ivan Enderlin, 2016-02-05T17:03:53+01:00)
  * Llk: Introduce the “save” parser! (Ivan Enderlin, 2016-01-25T14:26:48+01:00)
  * Llk: Token rule can be constructured as kept. (Ivan Enderlin, 2016-01-25T14:25:51+01:00)
  * Grammar: Reduce memory with transitional rules. (Ivan Enderlin, 2016-01-25T11:02:35+01:00)
  * Grammar: Reduce calls. (Ivan Enderlin, 2016-01-25T10:59:28+01:00)
  * Quality: Clean internal API. (Ivan Enderlin, 2016-01-23T09:30:39+01:00)
  * Quality: Fix CS. (Ivan Enderlin, 2016-01-22T17:11:26+01:00)
  * Parser: Use the lexer as an iterator. (Ivan Enderlin, 2016-01-22T08:43:39+01:00)
  * Grammar: Use the lexer as an iterator. (Ivan Enderlin, 2016-01-22T08:43:15+01:00)
  * Lexer: Transform it into an iterator (generator). (Ivan Enderlin, 2016-01-22T08:42:42+01:00)
  * PP: Remove the author. (Ivan Enderlin, 2016-01-17T14:16:54+01:00)
  * Update copyright. (Ivan Enderlin, 2016-01-17T14:15:44+01:00)

# 3.16.01.14

  * Composer: New stable libraries. (Ivan Enderlin, 2016-01-14T21:44:41+01:00)

# 3.16.01.11

  * Quality: Drop PHP5.4. (Ivan Enderlin, 2016-01-11T09:15:26+01:00)
  * Quality: Run devtools:cs. (Ivan Enderlin, 2016-01-09T08:58:15+01:00)
  * Core: Remove `Hoa\Core`. (Ivan Enderlin, 2016-01-09T08:03:04+01:00)
  * Consistency: Update `dnew` call. (Ivan Enderlin, 2015-12-09T16:44:21+01:00)
  * Consistency: Remove a call to `_define`. (Ivan Enderlin, 2015-12-08T22:27:56+01:00)
  * Consistency: Use `Hoa\Consistency`. (Ivan Enderlin, 2015-12-08T10:56:44+01:00)
  * Exception: Use `Hoa\Exception`. (Ivan Enderlin, 2015-11-20T07:15:35+01:00)
  * Documentation: Format API. (Ivan Enderlin, 2015-12-16T07:38:23+01:00)
  * Fix Llk\Llk::parsePP unrecognized instructions exception (lovenunu, 2015-12-14T16:48:09+01:00)

# 2.15.10.29

  * Test: Specify file type with `hoa://Test/Vfs`. (Ivan Enderlin, 2015-10-29T22:07:56+01:00)

# 2.15.10.21

  * Fix CS. (Ivan Enderlin, 2015-10-14T17:23:16+02:00)

# 2.15.08.25

  * Fix phpDoc. (Metalaka, 2015-08-12T20:35:36+02:00)
  * Add skip token generation. (Metalaka, 2014-08-25T17:09:56+02:00)
  * Add a `.gitignore` file. (Stéphane HULARD, 2015-08-03T11:22:38+02:00)

# 2.15.05.29

  * Move to PSR-1 and PSR-2. (Ivan Enderlin, 2015-05-04T20:11:09+02:00)

# 2.15.02.17

  * Add the CHANGELOG.md file. (Ivan Enderlin, 2015-02-17T09:33:56+01:00)
  * Update schemas in the documentation. (Ivan Enderlin, 2015-01-23T19:23:04+01:00)
  * Happy new year! (Ivan Enderlin, 2015-01-05T14:20:42+01:00)

# 2.14.12.10

  * Move to PSR-4. (Ivan Enderlin, 2014-12-09T13:40:43+01:00)

# 2.14.11.26

  * Require `hoa/test`. (Alexis von Glasow, 2014-11-25T23:17:38+01:00)
  * `Hoa\Visitor` has been finalized, update `composer.json`. (Ivan Enderlin, 2014-11-15T22:20:21+01:00)
  * Fix a bug in the unification. (Ivan Enderlin, 2014-11-11T15:11:18+01:00)

# 2.14.11.09

  * Use `hoa/iterator` `~1.0`. (Ivan Enderlin, 2014-11-09T10:58:53+01:00)
  * Move test into `Hoa\Json`. (Ivan Enderlin, 2014-10-04T12:14:45+02:00)
  * `Hoa\Json` has been released. (Ivan Enderlin, 2014-10-03T22:23:12+02:00)
  * Add the `getCompiler` method. (Ivan Enderlin, 2014-09-29T09:52:51+02:00)
  * Check soundness of other samplers. (Ivan Enderlin, 2014-09-29T09:46:47+02:00)
  * `Hoa\Regex` is required. (Ivan Enderlin, 2014-09-28T22:14:19+02:00)
  * Add soundness test. (Ivan Enderlin, 2014-09-28T22:09:20+02:00)
  * Fix API documentation. (Ivan Enderlin, 2014-09-26T22:35:10+02:00)
  * Remove `from`/`import` and update to PHP5.4. (Ivan Enderlin, 2014-09-26T11:14:35+02:00)
  * Update documentation. (Ivan Enderlin, 2014-09-26T10:58:43+02:00)

# 2.14.09.23

  * Add `branch-alias`. (Stéphane PY, 2014-09-23T11:50:46+02:00)

# 2.14.09.17

  * Drop PHP5.3. (Ivan Enderlin, 2014-09-17T17:06:10+02:00)
  * Add the installation section. (Ivan Enderlin, 2014-09-17T17:05:43+02:00)

(first snapshot)
